<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

final class BalanceService
{
    public function __construct(
        private readonly LedgerService $ledger,
        private readonly SettingsService $settings,
    ) {
    }

    public function forPharmacy(int $pharmacyId): array
    {
        $payments = DB::table('payments')
            ->where('pharmacy_id', $pharmacyId)
            ->whereNotNull('settlement_status')
            ->selectRaw('settlement_status, COALESCE(SUM(net_kobo), 0) as total')
            ->groupBy('settlement_status')
            ->pluck('total', 'settlement_status');

        $settlements = DB::table('settlements')
            ->where('pharmacy_id', $pharmacyId)
            ->selectRaw('status, COALESCE(SUM(net_kobo), 0) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $pending = (int) ($payments[PaymentSettlementStatus::Pending->value] ?? 0);
        $held = (int) ($payments[PaymentSettlementStatus::Held->value] ?? 0);
        $eligible = (int) ($payments[PaymentSettlementStatus::Eligible->value] ?? 0);

        $inTransit = (int) ($settlements[SettlementStatus::Pending->value] ?? 0)
            + (int) ($settlements[SettlementStatus::Processing->value] ?? 0)
            + (int) ($settlements[SettlementStatus::OnHold->value] ?? 0);

        $paidOut = (int) ($settlements[SettlementStatus::Success->value] ?? 0);

        $ledgerBalance = $this->ledger->balance($pharmacyId);
        $payable = $ledgerBalance - $pending - $held;
        $available = max(0, $payable);
        $debt = max(0, -$payable);

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($pharmacyId);
        $cooldown = $pharmacy?->getAttribute('payout_hold_until');
        $cooldownUntil = $cooldown !== null && Carbon::parse($cooldown)->isFuture()
            ? Carbon::parse($cooldown)->toIso8601String()
            : null;

        return [
            'currency' => (string) config('settlement.paystack.currency'),
            'pending_kobo' => $pending,
            'pending' => Money::toNaira($pending),
            'held_kobo' => $held,
            'held' => Money::toNaira($held),
            'eligible_kobo' => $eligible,
            'eligible' => Money::toNaira($eligible),
            'available_kobo' => $available,
            'available' => Money::toNaira($available),
            'carried_debt_kobo' => $debt,
            'carried_debt' => Money::toNaira($debt),
            'in_transit_kobo' => $inTransit,
            'in_transit' => Money::toNaira($inTransit),
            'paid_out_kobo' => $paidOut,
            'paid_out' => Money::toNaira($paidOut),
            'ledger_balance_kobo' => $ledgerBalance,
            'ledger_balance' => Money::toNaira($ledgerBalance),
            'minimum_payout_kobo' => (int) config('settlement.payout.minimum_kobo'),
            'payouts_paused' => $this->settings->payoutsPaused(),
            'payout_on_hold' => $pharmacy !== null && (bool) $pharmacy->getAttribute('payout_hold'),
            'payout_cooldown_until' => $cooldownUntil,
            'payout_schedule' => (string) ($pharmacy?->getAttribute('payout_schedule') ?? 'daily'),
            'is_test_account' => $pharmacy !== null && (bool) $pharmacy->getAttribute('is_test_account'),
            'batch_at' => (string) config('settlement.schedule.batch_at'),
            'timezone' => (string) config('settlement.schedule.timezone'),
            'weekly_day' => (int) config('settlement.payout.weekly_day'),
            'hold_hours' => (int) config('settlement.eligibility.hold_hours'),
            'account_change_cooldown_hours' => (int) config('settlement.payout.account_change_cooldown_hours'),
        ];
    }
}
