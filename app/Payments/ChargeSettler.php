<?php

declare(strict_types=1);

namespace App\Payments;

use App\Models\Payment;
use App\Models\SubscriptionPayment;
use App\Services\Billing\PharmacySubscriptionService;
use App\Services\Payments\PaymentService;

class ChargeSettler
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly PharmacySubscriptionService $subscriptions,
    ) {
    }

    public function settle(string $reference): bool
    {
        if (Payment::query()->withoutGlobalScopes()->where('reference', $reference)->exists()) {
            $this->payments->handleSuccessfulCharge($reference);

            return true;
        }

        if (SubscriptionPayment::query()->withoutGlobalScopes()->where('reference', $reference)->exists()) {
            $this->subscriptions->settleReference($reference);

            return true;
        }

        return false;
    }
}
