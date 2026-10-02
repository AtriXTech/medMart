<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Jobs\InitiateTransferJob;
use App\Settlement\Support\Money;
use Illuminate\Support\Facades\DB;

final class SettlementLifecycleService
{
    public function __construct(
        private readonly LedgerService $ledger,
        private readonly AuditLogger $audit,
        private readonly Notifier $notifier,
    ) {
    }

    public function markSucceeded(int $settlementId, ?string $transferCode = null): void
    {
        $succeeded = null;

        DB::transaction(function () use ($settlementId, $transferCode, &$succeeded): void {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null || $settlement->status === SettlementStatus::Success) {
                return;
            }

            if (in_array($settlement->status, [SettlementStatus::Failed, SettlementStatus::Reversed, SettlementStatus::Cancelled], true)) {
                $this->quarantineLateSuccess($settlement);

                return;
            }

            $settlement->forceFill([
                'status' => SettlementStatus::Success,
                'processed_at' => now(),
                'failure_reason' => null,
                'hold_reason' => null,
                'gateway_reference' => $transferCode ?? $settlement->gateway_reference,
            ])->save();

            Pharmacy::query()->withoutGlobalScopes()->whereKey($settlement->pharmacy_id)->update(['consecutive_payout_failures' => 0]);

            $this->audit->record('settlement.succeeded', $settlement->pharmacy_id, $settlement, null, ['reference' => $settlement->reference]);

            $succeeded = $settlement;
        });

        if ($succeeded instanceof Settlement) {
            $this->notifier->pharmacy($succeeded->pharmacy_id, 'Your settlement has been sent', [
                'We have sent ' . Money::format((int) $succeeded->net_kobo) . ' to your settlement account.',
                'Reference: ' . $succeeded->reference,
                'It may take a short while to reflect, depending on your bank.',
            ]);
        }
    }

    public function markFailed(int $settlementId, SettlementStatus $to, string $reason): bool
    {
        $applied = DB::transaction(function () use ($settlementId, $to, $reason): bool {
            [$pharmacy, $settlement] = $this->lock($settlementId);

            if ($settlement === null) {
                return false;
            }

            $allowed = [SettlementStatus::Pending, SettlementStatus::Processing, SettlementStatus::OnHold, SettlementStatus::Success];

            if (! in_array($settlement->status, $allowed, true)) {
                return false;
            }

            $this->unwind($settlement, $to, $reason, null);
            $this->registerFailure($pharmacy, $settlement);

            return true;
        });

        if ($applied) {
            $settlement = Settlement::query()->find($settlementId);

            if ($settlement !== null) {
                $this->notifier->pharmacy($settlement->pharmacy_id, 'Your settlement could not be completed', [
                    'A settlement of ' . Money::format((int) $settlement->net_kobo) . ' did not go through.',
                    'The funds remain in your balance and will be included in a later settlement.',
                    'Reference: ' . $settlement->reference,
                ]);

                $this->notifier->ops('Settlement ' . $to->value, [
                    'Reference: ' . $settlement->reference,
                    'Pharmacy ID: ' . $settlement->pharmacy_id,
                    'Amount: ' . Money::format((int) $settlement->net_kobo),
                    'Reason: ' . $reason,
                ], 'error');
            }
        }

        return $applied;
    }

    public function cancel(int $settlementId, string $reason, ?int $actorId): void
    {
        DB::transaction(function () use ($settlementId, $reason, $actorId): void {
            [, $settlement] = $this->lock($settlementId);

            if ($settlement === null) {
                throw SettlementException::notFound('Settlement not found.');
            }

            if (! in_array($settlement->status, [SettlementStatus::Pending, SettlementStatus::OnHold], true)) {
                throw SettlementException::invalidState('Only pending or on-hold settlements can be cancelled.');
            }

            $this->unwind($settlement, SettlementStatus::Cancelled, $reason, $actorId);
        });
    }

    public function hold(int $settlementId, string $reason, ?int $actorId = null, array $from = [SettlementStatus::Pending]): void
    {
        $held = DB::transaction(function () use ($settlementId, $reason, $actorId, $from): ?Settlement {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null || ! in_array($settlement->status, $from, true)) {
                return null;
            }

            $settlement->forceFill(['status' => SettlementStatus::OnHold, 'hold_reason' => mb_substr($reason, 0, 255)])->save();

            $this->audit->record('settlement.held', $settlement->pharmacy_id, $settlement, $actorId, ['reason' => $reason]);

            return $settlement;
        });

        if ($held !== null) {
            $this->notifier->ops('Settlement placed on hold', [
                'Reference: ' . $held->reference,
                'Reason: ' . $reason,
            ], $reason === 'otp_required' ? 'critical' : 'warning');
        } elseif ($actorId !== null) {
            throw SettlementException::invalidState('This settlement cannot be placed on hold in its current state.');
        }
    }

    public function release(int $settlementId, ?int $actorId): void
    {
        $next = DB::transaction(function () use ($settlementId, $actorId): SettlementStatus {
            $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

            if ($settlement === null) {
                throw SettlementException::notFound('Settlement not found.');
            }

            if ($settlement->status !== SettlementStatus::OnHold) {
                throw SettlementException::invalidState('Only on-hold settlements can be released.');
            }

            $next = $settlement->gateway_reference !== null ? SettlementStatus::Processing : SettlementStatus::Pending;

            $settlement->forceFill(['status' => $next, 'hold_reason' => null, 'last_attempt_at' => now()])->save();

            $this->audit->record('settlement.released', $settlement->pharmacy_id, $settlement, $actorId);

            return $next;
        });

        if ($next === SettlementStatus::Pending) {
            InitiateTransferJob::dispatch($settlementId);
        }
    }

    public function releasePaused(?int $actorId): int
    {
        $released = 0;

        Settlement::query()
            ->where('status', SettlementStatus::OnHold->value)
            ->where('hold_reason', 'payouts_paused')
            ->orderBy('id')
            ->pluck('id')
            ->each(function (int $id) use ($actorId, &$released): void {
                $this->release($id, $actorId);
                $released++;
            });

        return $released;
    }

    private function lock(int $settlementId): array
    {
        $probe = Settlement::query()->find($settlementId);

        if ($probe === null) {
            return [null, null];
        }

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->whereKey($probe->pharmacy_id)->lockForUpdate()->first();
        $settlement = Settlement::query()->whereKey($settlementId)->lockForUpdate()->first();

        return [$pharmacy, $settlement];
    }

    private function unwind(Settlement $settlement, SettlementStatus $to, string $reason, ?int $actorId): void
    {
        $this->ledger->post(
            $settlement->pharmacy_id,
            LedgerEntryType::PayoutReversal,
            (int) $settlement->net_kobo,
            'payout:' . $settlement->id . ':reversal',
            ['settlement_id' => $settlement->id],
            ['reason' => $reason]
        );

        $paymentIds = DB::table('settlement_payments')->where('settlement_id', $settlement->id)->pluck('payment_id')->all();

        foreach (array_chunk($paymentIds, 1000) as $chunk) {
            Payment::query()
                ->withoutGlobalScopes()
                ->whereIn('id', $chunk)
                ->where('settlement_status', PaymentSettlementStatus::Settled->value)
                ->update(['settlement_status' => PaymentSettlementStatus::Eligible->value]);
        }

        $settlement->forceFill([
            'status' => $to,
            'failure_reason' => mb_substr($reason, 0, 255),
            'hold_reason' => null,
            'processed_at' => now(),
        ])->save();

        $this->audit->record('settlement.' . $to->value, $settlement->pharmacy_id, $settlement, $actorId, [
            'reference' => $settlement->reference,
            'reason' => $reason,
            'payments' => count($paymentIds),
        ]);
    }

    private function registerFailure(?Pharmacy $pharmacy, Settlement $settlement): void
    {
        if ($pharmacy === null) {
            return;
        }

        $failures = (int) $pharmacy->getAttribute('consecutive_payout_failures') + 1;
        $update = ['consecutive_payout_failures' => $failures];
        $limit = (int) config('settlement.payout.max_consecutive_failures');

        if ($limit > 0 && $failures >= $limit && ! (bool) $pharmacy->getAttribute('payout_hold')) {
            $update['payout_hold'] = true;
            $update['payout_hold_reason'] = 'consecutive_payout_failures';

            DB::afterCommit(function () use ($pharmacy, $failures): void {
                $this->notifier->ops('Pharmacy payouts auto-held after repeated failures', [
                    'Pharmacy ID: ' . $pharmacy->id,
                    'Consecutive failures: ' . $failures,
                ], 'critical');
            });
        }

        Pharmacy::query()->withoutGlobalScopes()->whereKey($pharmacy->id)->update($update);
    }

    private function quarantineLateSuccess(Settlement $settlement): void
    {
        Pharmacy::query()->withoutGlobalScopes()->whereKey($settlement->pharmacy_id)->update([
            'payout_hold' => true,
            'payout_hold_reason' => 'late_transfer_success',
        ]);

        $this->audit->record('settlement.late_success_detected', $settlement->pharmacy_id, $settlement, null, [
            'reference' => $settlement->reference,
            'status' => $settlement->status->value,
        ]);

        DB::afterCommit(function () use ($settlement): void {
            $this->notifier->ops('Transfer succeeded after settlement was released', [
                'Reference: ' . $settlement->reference,
                'Pharmacy ID: ' . $settlement->pharmacy_id,
                'Payouts for this pharmacy have been placed on hold. Reconcile manually before releasing.',
            ], 'critical');
        });
    }
}
