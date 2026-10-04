<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Settlement\Support\Money;

final class CommissionCalculator
{
    public function __construct(private readonly SettingsService $settings)
    {
    }

    public function platformFeeKobo(Pharmacy $pharmacy, int $grossKobo): int
    {
        if (! $this->settings->commissionEnabled() || $grossKobo <= 0) {
            return 0;
        }

        $override = $pharmacy->getAttribute('commission_rate');
        $rate = $override !== null ? (float) $override : $this->settings->commissionRate();

        $fee = Money::percentOf($grossKobo, $rate) + $this->settings->commissionFlatKobo();

        $cap = $this->settings->commissionCapKobo();

        if ($cap > 0) {
            $fee = min($fee, $cap);
        }

        return max(0, min($fee, $grossKobo));
    }
}
