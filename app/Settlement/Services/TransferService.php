<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Payments\Contracts\PaymentGateway;
use App\Payments\Data\TransferRequest;
use App\Payments\Data\TransferResult;
use App\Payments\Enums\TransferState;
use App\Payments\Exceptions\GatewayException;
use App\Payments\GatewayManager;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\InsufficientBalanceException;
use App\Settlement\Jobs\InitiateTransferJob;
use App\Settlement\Support\Money;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

final class TransferService
{
    public function __construct(
        private readonly GatewayManager $gateways,
        private readonly PayoutDestinationResolver $destinations,
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

        $gateway = $settlement->gateway !== null
            ? $this->stickyGateway($settlement)
            : $this->gateways->payouts();

        if ((bool) config('settlement.transfer.check_balance')) {
            $balance = $gateway->balanceKobo();

            if ($balance < (int) $settlement->net_kobo) {
                $this->alertLowBalance($gateway, $balance, (int) $settlement->net_kobo);

                throw new InsufficientBalanceException();
            }
        }

        try {
            $destination = $this->destinations->forSettlement($settlement, $gateway);
        } catch (GatewayException $exception) {
            if ($exception->isAmbiguous()) {
                throw $exception;
            }

            $this->lifecycle->hold($settlementId, 'account_rejected_by_gateway', null, [SettlementStatus::Pending]);

            return;
        }

        $prepared = $this->prepare($settlementId, $gateway);

        if ($prepared === null || $destination === null) {
            return;
        }

        try {
            $result = $gateway->initiateTransfer(new TransferRequest(
                $prepared['reference'],
                $prepared['amount'],
                (string) config('settlement.payout.narration'),
                $destination
            ));
        } catch (GatewayException $exception) {
            $this->handleInitiationError($settlementId, $gateway, $exception);

            return;
        }

        $this->handleInitiationResult($settlementId, $result);
    }

    public function verifyAndApply(Settlement $settlement): string
    {
        $gateway = $this->gateways->for($settlement->gateway);
        $result = $gateway->verifyTransfer($settlement->reference);

        if ($result === null) {
            return 'not_found';
        }

        if ($result->gatewayReference !== null && $settlement->gateway_reference === null) {
            Settlement::query()->whereKey($settlement->id)->update(['gateway_reference' => $result->gatewayReference]);
        }

        $this->events->apply($settlement, $result);

        return $result->state->isFinal() ? 'applied' : 'pending';
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

    private function prepare(int $settlementId, PaymentGateway $gateway): ?array
    {
        return DB::transaction(function () use ($settlementId, $gateway): ?array {
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
                'gateway' => $settlement->gateway ?? $gateway->gateway()->value,
            ])->save();

            return [
                'reference' => $settlement->reference,
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

        if ($account === null || $account->status !== AccountStatus::Approved) {
            return 'account_not_approved';
        }

        if ((int) $settlement->attempts >= (int) config('settlement.transfer.max_attempts')) {
            return 'max_attempts_exceeded';
        }

        return null;
    }

    private function handleInitiationError(int $settlementId, PaymentGateway $gateway, GatewayException $exception): void
    {
        if ($exception->isAmbiguous()) {
            Log::warning('[settlement] Transfer initiation outcome unknown', [
                'settlement_id' => $settlementId,
                'gateway' => $gateway->gateway()->value,
                'message' => $exception->getMessage(),
            ]);

            return;
        }

        if ($exception->isInsufficientBalance()) {
            $this->revertToPending($settlementId);
            $this->alertLowBalance($gateway, null, null);

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

    private function handleInitiationResult(int $settlementId, TransferResult $result): void
    {
        if ($result->gatewayReference !== null) {
            Settlement::query()->whereKey($settlementId)->update(['gateway_reference' => $result->gatewayReference]);
        }

        $settlement = Settlement::query()->find($settlementId);

        if ($settlement === null || $result->state === TransferState::Pending) {
            return;
        }

        $this->events->apply($settlement, $result);
    }

    private function stickyGateway(Settlement $settlement): PaymentGateway
    {
        $gateway = $this->gateways->for($settlement->gateway);

        if (! $gateway->isConfigured()) {
            throw GatewayException::misconfigured($gateway->gateway()->label());
        }

        return $gateway;
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

    private function alertLowBalance(PaymentGateway $gateway, ?int $balanceKobo, ?int $requiredKobo): void
    {
        $label = $gateway->gateway()->label();

        if (! Cache::add('settlement:low-balance-alert:' . $gateway->gateway()->value, 1, 1800)) {
            return;
        }

        $this->notifier->ops($label . ' balance too low for payouts', [
            'Available: ' . ($balanceKobo === null ? 'unknown' : Money::format($balanceKobo)),
            'Required for next transfer: ' . ($requiredKobo === null ? 'unknown' : Money::format($requiredKobo)),
            'Top up the ' . $label . ' balance. Transfers will retry automatically.',
        ], 'critical');
    }
}
