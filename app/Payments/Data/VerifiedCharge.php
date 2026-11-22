<?php

declare(strict_types=1);

namespace App\Payments\Data;

use App\Payments\Enums\ChargeState;

final readonly class VerifiedCharge
{
    public function __construct(
        public ChargeState $state,
        public string $reference,
        public ?string $gatewayReference,
        public int $amountKobo,
        public string $currency,
        public ?int $feeKobo,
        public ?string $rawStatus,
        public array $raw = [],
    ) {
    }

    public function isSuccessful(): bool
    {
        return $this->state === ChargeState::Success;
    }
}
