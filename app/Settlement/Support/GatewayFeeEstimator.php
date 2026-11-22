<?php

declare(strict_types=1);

namespace App\Settlement\Support;

use App\Payments\Enums\Gateway;

final class GatewayFeeEstimator
{
    public function estimate(int $amountKobo, Gateway $gateway): int
    {
        $rules = config('settlement.fees.' . $gateway->value);
        $rules = is_array($rules) ? $rules : (array) config('settlement.fees');

        $fee = Money::percentOf($amountKobo, (float) ($rules['percent'] ?? 0));

        if ($amountKobo >= (int) ($rules['flat_threshold_kobo'] ?? 0)) {
            $fee += (int) ($rules['flat_kobo'] ?? 0);
        }

        $cap = (int) ($rules['cap_kobo'] ?? 0);

        return $cap > 0 ? min($fee, $cap) : $fee;
    }
}
