<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\InsufficientBalanceException;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Jobs\InitiateTransferJob;
use App\Settlement\Support\Money;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TransferService
{
    public function __construct(
        private readonly PaystackClient $paystack,
        private readonly SettingsService $settings,
        private readonly PayoutPolicy $policy,
        private readonly SettlementLifecycleService $lifecycle,
        private readonly TransferEventProcessor $events,
        private readonly AuditLogger $audit,
        private readonly Notifier $notifier,
    ) {
    }

    public function initiate(int $settlementId): void
    {
        $settlement = Settlement::query()->find($settlementId);

        if ($settlement === null || $settlement->status !== SettlementStatus::Pending) {
            return;
        }

        if ((bool) config('settlement.transfer.check_balance')) {
            $balance = $this->paystack->balance();

            if ($balance < (int) $settlement->net_kobo) {
                $this->alertLowBalance($balance, (int) $settlement->net_kobo);

                throw new InsufficientBalanceException();
            }
        }

        $prepared = $this->prepare($settlementId);

        if ($prepared === null) {
            return;
        }

        try {
            $data = $this->paystack->initiateTransfer(
                $prepared['reference'],
                $prepared['recipient'],
                $prepared['amount'],
                (string) config('settlement.payout.narration')
            );
        } catch (PaystackException $exception) {
            $this->handleInitiationError($settlementId, $exception);

            return;
        }

        $this->handleInitiationResponse($settlementId, $data);
    }

    public function verifyAndApply(Settlement $settlement): string
    {
        $data = $this->paystack->verifyTransfer($settlement->reference);

        if ($data === null) {
            return 'not_found';
        }

        $status = strtolower((string) ($data['status'] ?? ''));

        if (isset($data['transfer_code']) && $settlement->gateway_reference === null) {
            Settlement::query()->whereKey($settlement->id)->update(['gateway_reference' => (string) $data['transfer_code']]);
        }

        $this->events->apply($settlement, $status, $data);

        return in_array($status, ['success', 'failed', 'reversed', 'otp'], true) ? 'applied' : 'pending';
    }

    public function resetUnsubmitted(int $settlementId): bool
    {
        $outcome = DB::transaction(function () use ($settlementId): ?string {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null || $settlement->status !== SettlementStatus::Processing || $settlement->gateway_reference !== null) {
                return null;
            }

            if ((int) $settlement->attempts >= (int) config('settlement.transfer.max_attempts')) {
                $settlement->forceFill(['status' => SettlementStatus::OnHold, 'hold_reason' => 'max_attempts_exceeded'])->save();
                $this->audit->record('settlement.held', $settlement->pharmacy_id, $settlement, null, ['reason' => 'max_attempts_exceeded']);

                return 'held';
            }

            $settlement->forceFill(['status' => SettlementStatus::Pending])->save();

            return 'pending';
        });

        if ($outcome === 'pending') {
            InitiateTransferJob::dispatch($settlementId);
        }

        if ($outcome === 'held') {
            $this->notifier->ops('Settlement held after repeated unsuccessful attempts', ['Settlement ID: ' . $settlementId], 'critical');
        }

        return $outcome !== null;
    }

    private function prepare(int $settlementId): ?array
    {
        return DB::transaction(function () use ($settlementId): ?array {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null || $settlement->status !== SettlementStatus::Pending) {
                return null;
            }

            $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($settlement->pharmacy_id);
            $account = SettlementAccount::query()->withoutGlobalScopes()->find($settlement->settlement_account_id);

            $blocked = $this->blockingReason($settlement, $pharmacy, $account);

            if ($blocked !== null) {
                $settlement->forceFill(['status' => SettlementStatus::OnHold, 'hold_reason' => $blocked])->save();
                $this->audit->record('settlement.held', $settlement->pharmacy_id, $settlement, null, ['reason' => $blocked]);

                return null;
            }

            $settlement->forceFill([
                'status' => SettlementStatus::Processing,
                'attempts' => (int) $settlement->attempts + 1,
                'last_attempt_at' => now(),
                'initiated_at' => $settlement->initiated_at ?? now(),
                'hold_reason' => null,
            ])->save();

            return [
                'reference' => $settlement->reference,
                'recipient' => (string) $account->paystack_recipient_code,
                'amount' => (int) $settlement->net_kobo,
            ];
        });
    }

    private function blockingReason(Settlement $settlement, ?Pharmacy $pharmacy, ?SettlementAccount $account): ?string
    {
        if ($this->settings->payoutsPaused()) {
            return 'payouts_paused';
        }

        if ($pharmacy === null || ! $this->policy->canReceivePayouts($pharmacy)) {
            return 'pharmacy_payout_hold';
        }

        if ($account === null || $account->status !== AccountStatus::Approved || empty($account->paystack_recipient_code)) {
            return 'account_not_approved';
        }

        if ((int) $settlement->attempts >= (int) config('settlement.transfer.max_attempts')) {
            return 'max_attempts_exceeded';
        }

        return null;
    }

    private function handleInitiationError(int $settlementId, PaystackException $exception): void
    {
        if ($exception->isAmbiguous()) {
            Log::warning('[settlement] Transfer initiation outcome unknown', [
                'settlement_id' => $settlementId,
                'message' => $exception->getMessage(),
            ]);

            return;
        }

        if ($exception->isInsufficientBalance()) {
            $this->revertToPending($settlementId);
            $this->alertLowBalance(null, null);

            throw new InsufficientBalanceException();
        }

        if ($exception->isDuplicateReference()) {
            $settlement = Settlement::query()->find($settlementId);

            if ($settlement !== null) {
                $this->verifyAndApply($settlement);
            }

            return;
        }

        $this->lifecycle->markFailed($settlementId, SettlementStatus::Failed, mb_substr($exception->getMessage(), 0, 255));
    }

    private function handleInitiationResponse(int $settlementId, array $data): void
    {
        $status = strtolower((string) ($data['status'] ?? ''));
        $code = isset($data['transfer_code']) ? (string) $data['transfer_code'] : null;

        if ($code !== null) {
            Settlement::query()->whereKey($settlementId)->update(['gateway_reference' => $code]);
        }

        match ($status) {
            'success' => $this->lifecycle->markSucceeded($settlementId, $code),
            'otp' => $this->lifecycle->hold($settlementId, 'otp_required', null, [SettlementStatus::Processing]),
            'failed', 'reversed' => $this->lifecycle->markFailed(
                $settlementId,
                $status === 'failed' ? SettlementStatus::Failed : SettlementStatus::Reversed,
                'Transfer ' . $status . ' at gateway.'
            ),
            default => null,
        };
    }

    private function revertToPending(int $settlementId): void
    {
        DB::transaction(function () use ($settlementId): void {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null || $settlement->status !== SettlementStatus::Processing || $settlement->gateway_reference !== null) {
                return;
            }

            $settlement->forceFill([
                'status' => SettlementStatus::Pending,
                'attempts' => max(0, (int) $settlement->attempts - 1),
            ])->save();
        });
    }

    private function alertLowBalance(?int $balanceKobo, ?int $requiredKobo): void
    {
        if (! Cache::add('settlement:low-balance-alert', 1, 1800)) {
            return;
        }

        $this->notifier->ops('Paystack balance too low for payouts', [
            'Available: ' . ($balanceKobo === null ? 'unknown' : Money::format($balanceKobo)),
            'Required for next transfer: ' . ($requiredKobo === null ? 'unknown' : Money::format($requiredKobo)),
            'Top up the Paystack balance. Transfers will retry automatically.',
        ], 'critical');
    }
}
