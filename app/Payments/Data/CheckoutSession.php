<?php

declare(strict_types=1);

namespace App\Payments\Data;

final readonly class CheckoutSession
{
    public function __construct(
        public string $authorizationUrl,
        public string $reference,
        public ?string $gatewayReference = null,
    ) {
    }
}
