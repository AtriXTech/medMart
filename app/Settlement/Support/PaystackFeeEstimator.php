<?php

declare(strict_types=1);

namespace App\Settlement\Support;

final class PaystackFeeEstimator
{
    public function estimate(int $amountKobo): int
    {
        $fee = Money::percentOf($amountKobo, (float) config('settlement.fees.percent'));

        if ($amountKobo >= (int) config('settlement.fees.flat_threshold_kobo')) {
            $fee += (int) config('settlement.fees.flat_kobo');
        }

        $cap = (int) config('settlement.fees.cap_kobo');

        return $cap > 0 ? min($fee, $cap) : $fee;
    }
}
