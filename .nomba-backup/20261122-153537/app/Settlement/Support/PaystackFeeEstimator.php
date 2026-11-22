<?php

declare(strict_types=1);

namespace App\Settlement\Support;

use App\Payments\Enums\Gateway;

final class PaystackFeeEstimator
{
    public function __construct(private readonly GatewayFeeEstimator $estimator)
    {
    }

    public function estimate(int $amountKobo): int
    {
        return $this->estimator->estimate($amountKobo, Gateway::Paystack);
    }
}
