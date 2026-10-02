<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Payment;
use App\Models\Pharmacy;
use App\Models\Settlement;
use App\Models\SettlementAccount;
use App\Settlement\Enums\AccountStatus;
use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Jobs\InitiateTransferJob;
use App\Settlement\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

final class SettlementBatchService
{
    public function __construct(
        private readonly SettingsService $settings,
        private readonly LedgerService $ledger,
        private readonly PayoutPolicy $policy,
        private readonly AuditLogger $audit,
    ) {
    }

    public function runAll(): array
    {
        $summary = ['created' => 0, 'skipped' => 0, 'failed' => 0, 'paused' => false];

        if ($this->settings->payoutsPaused()) {
            $summary['paused'] = true;

            return $summary;
        }

        $pharmacyIds = Payment::query()
            ->withoutGlobalScopes()
            ->where('settlement_status', PaymentSettlementStatus::Eligible->value)
            ->distinct()
            ->pluck('pharmacy_id');

        foreach ($pharmacyIds as $pharmacyId) {
            try {
                $settlement = $this->createForPharmacy((int) $pharmacyId, true);
                $settlement !== null ? $summary['created']++ : $summary['skipped']++;
            } catch (Throwable $exception) {
                $summary['failed']++;
                report($exception);
            }
        }

        return $summary;
    }

    public function createForPharmacy(int $pharmacyId, bool $respectSchedule): ?Settlement
    {
        if ($this->settings->payoutsPaused()) {
            return null;
        }

        $settlement = DB::transaction(function () use ($pharmacyId, $respectSchedule): ?Settlement {
            $pharmacy = Pharmacy::query()->withoutGlobalScopes()->whereKey($pharmacyId)->lockForUpdate()->first();

            if ($pharmacy === null || ! $this->policy->canReceivePayouts($pharmacy)) {
                return null;
            }

            if ($respectSchedule && ! $this->policy->scheduleAllowsToday($pharmacy)) {
                return null;
            }

            $account = SettlementAccount::query()
                ->withoutGlobalScopes()
                ->where('pharmacy_id', $pharmacyId)
                ->where('status', AccountStatus::Approved->value)
                ->whereNotNull('paystack_recipient_code')
                ->orderByDesc('id')
                ->first();

            if ($account === null) {
                return null;
            }

            $payments = Payment::query()
                ->withoutGlobalScopes()
                ->where('pharmacy_id', $pharmacyId)
                ->where('settlement_status', PaymentSettlementStatus::Eligible->value)
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id', 'gross_kobo', 'net_kobo']);

            if ($payments->isEmpty()) {
                return null;
            }

            $nonPayable = (int) DB::table('payments')
                ->where('pharmacy_id', $pharmacyId)
                ->whereIn('settlement_status', [PaymentSettlementStatus::Pending->value, PaymentSettlementStatus::Held->value])
                ->sum('net_kobo');

            $payable = $this->ledger->balance($pharmacyId) - $nonPayable;

            if ($payable < max(1, (int) config('settlement.payout.minimum_kobo'))) {
                return null;
            }

            $eligibleNet = (int) $payments->sum(static fn (Payment $payment): int => (int) $payment->net_kobo);
            $eligibleGross = (int) $payments->sum(static fn (Payment $payment): int => (int) $payment->gross_kobo);
            $ceiling = (int) config('settlement.payout.max_single_kobo');
            $overCeiling = $ceiling > 0 && $payable > $ceiling;

            $settlement = Settlement::query()->create([
                'pharmacy_id' => $pharmacyId,
                'settlement_account_id' => $account->id,
                'reference' => $this->newReference(),
                'amount' => Money::toNaira($payable),
                'status' => $overCeiling ? SettlementStatus::OnHold : SettlementStatus::Pending,
                'gross_kobo' => $eligibleGross,
                'fee_kobo' => max(0, $eligibleGross - $eligibleNet),
                'adjustment_kobo' => $payable - $eligibleNet,
                'net_kobo' => $payable,
                'attempts' => 0,
                'hold_reason' => $overCeiling ? 'exceeds_payout_ceiling' : null,
            ]);

            $paymentIds = $payments->pluck('id')->all();

            foreach (array_chunk($paymentIds, 1000) as $chunk) {
                $settlement->payments()->attach($chunk);

                $updated = Payment::query()
                    ->withoutGlobalScopes()
                    ->whereIn('id', $chunk)
                    ->where('settlement_status', PaymentSettlementStatus::Eligible->value)
                    ->update(['settlement_status' => PaymentSettlementStatus::Settled->value]);

                if ($updated !== count($chunk)) {
                    throw SettlementException::invalidState('Payments changed state while creating settlement ' . $settlement->reference . '.');
                }
            }

            $this->ledger->post(
                $pharmacyId,
                LedgerEntryType::PayoutDebit,
                -$payable,
                'payout:' . $settlement->id . ':debit',
                ['settlement_id' => $settlement->id],
                ['reference' => $settlement->reference]
            );

            $this->audit->record('settlement.created', $pharmacyId, $settlement, null, [
                'reference' => $settlement->reference,
                'net_kobo' => $payable,
                'payments' => count($paymentIds),
                'on_hold' => $overCeiling,
            ]);

            return $settlement;
        });

        if ($settlement !== null && $settlement->status === SettlementStatus::Pending) {
            InitiateTransferJob::dispatch($settlement->id);
        }

        return $settlement;
    }

    public function diagnose(int $pharmacyId): string
    {
        if ($this->settings->payoutsPaused()) {
            return 'All payouts are paused. Resume them in Controls.';
        }

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($pharmacyId);

        if ($pharmacy === null) {
            return 'Pharmacy not found.';
        }

        if ((bool) $pharmacy->getAttribute('is_test_account')) {
            return 'This pharmacy is flagged as a test account (is_test_account), so payouts are skipped. Set is_test_account to false to test payouts.';
        }

        if ((bool) $pharmacy->getAttribute('payout_hold')) {
            return 'Payouts are on hold for this pharmacy. Release the hold in the Pharmacy tab.';
        }

        $until = $pharmacy->getAttribute('payout_hold_until');

        if ($until !== null && Carbon::parse($until)->isFuture()) {
            return 'Payouts are in the security cooldown after an account change, until ' . Carbon::parse($until)->toDayDateTimeString() . '.';
        }

        $hasAccount = SettlementAccount::query()
            ->withoutGlobalScopes()
            ->where('pharmacy_id', $pharmacyId)
            ->where('status', AccountStatus::Approved->value)
            ->whereNotNull('paystack_recipient_code')
            ->exists();

        if (! $hasAccount) {
            return 'No approved settlement account with a Paystack recipient. Approve one in Account Review.';
        }

        $eligible = Payment::query()
            ->withoutGlobalScopes()
            ->where('pharmacy_id', $pharmacyId)
            ->where('settlement_status', PaymentSettlementStatus::Eligible->value)
            ->count();

        if ($eligible === 0) {
            return 'No eligible payments yet. Payments become eligible after the hold window; use Promote eligible.';
        }

        $nonPayable = (int) DB::table('payments')
            ->where('pharmacy_id', $pharmacyId)
            ->whereIn('settlement_status', [PaymentSettlementStatus::Pending->value, PaymentSettlementStatus::Held->value])
            ->sum('net_kobo');

        $payable = $this->ledger->balance($pharmacyId) - $nonPayable;
        $minimum = max(1, (int) config('settlement.payout.minimum_kobo'));

        if ($payable < $minimum) {
            return 'Payable balance (' . Money::format($payable) . ') is below the minimum payout (' . Money::format($minimum) . ').';
        }

        return 'Nothing to settle right now.';
    }

    private function newReference(): string
    {
        return 'stl_' . strtolower((string) Str::ulid());
    }
}