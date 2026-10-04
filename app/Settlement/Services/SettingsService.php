<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Settlement\Enums\FeeBearer;
use App\Settlement\Models\SettlementSetting;

final class SettingsService
{
    public function commissionEnabled(): bool
    {
        return (bool) SettlementSetting::read('commission_enabled', config('settlement.commission.enabled'));
    }

    public function commissionRate(): float
    {
        return (float) SettlementSetting::read('commission_rate', config('settlement.commission.rate'));
    }

    public function commissionFlatKobo(): int
    {
        return (int) SettlementSetting::read('commission_flat_kobo', config('settlement.commission.flat_kobo'));
    }

    public function commissionCapKobo(): int
    {
        return (int) SettlementSetting::read('commission_cap_kobo', config('settlement.commission.cap_kobo'));
    }

    public function gatewayFeeBearer(): FeeBearer
    {
        $value = (string) SettlementSetting::read('gateway_fee_bearer', config('settlement.fees.gateway_fee_bearer'));

        return FeeBearer::tryFrom($value) ?? FeeBearer::Pharmacy;
    }

    public function payoutsPaused(): bool
    {
        return (bool) SettlementSetting::read('payouts_paused', false);
    }

    public function pauseReason(): ?string
    {
        $reason = SettlementSetting::read('pause_reason');

        return is_string($reason) && $reason !== '' ? $reason : null;
    }

    public function update(array $values, ?int $actorId): void
    {
        $casts = [
            'commission_enabled' => static fn (mixed $v): bool => (bool) $v,
            'commission_rate' => static fn (mixed $v): float => (float) $v,
            'commission_flat_kobo' => static fn (mixed $v): int => (int) $v,
            'commission_cap_kobo' => static fn (mixed $v): int => (int) $v,
            'gateway_fee_bearer' => static fn (mixed $v): string => (FeeBearer::tryFrom((string) $v) ?? FeeBearer::Pharmacy)->value,
        ];

        foreach ($casts as $key => $cast) {
            if (array_key_exists($key, $values)) {
                SettlementSetting::write($key, $cast($values[$key]), $actorId);
            }
        }
    }

    public function pause(string $reason, ?int $actorId): void
    {
        SettlementSetting::write('payouts_paused', true, $actorId);
        SettlementSetting::write('pause_reason', $reason, $actorId);
    }

    public function resume(?int $actorId): void
    {
        SettlementSetting::write('payouts_paused', false, $actorId);
        SettlementSetting::write('pause_reason', null, $actorId);
    }

    public function all(): array
    {
        return [
            'commission_enabled' => $this->commissionEnabled(),
            'commission_rate' => $this->commissionRate(),
            'commission_flat_kobo' => $this->commissionFlatKobo(),
            'commission_cap_kobo' => $this->commissionCapKobo(),
            'gateway_fee_bearer' => $this->gatewayFeeBearer()->value,
            'payouts_paused' => $this->payoutsPaused(),
            'pause_reason' => $this->pauseReason(),
            'minimum_payout_kobo' => (int) config('settlement.payout.minimum_kobo'),
            'max_single_payout_kobo' => (int) config('settlement.payout.max_single_kobo'),
            'hold_hours' => (int) config('settlement.eligibility.hold_hours'),
        ];
    }
}
