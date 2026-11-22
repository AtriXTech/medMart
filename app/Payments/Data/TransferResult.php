<?php

declare(strict_types=1);

namespace App\Payments\Data;

use App\Payments\Enums\TransferState;

final readonly class TransferResult
{
    public function __construct(
        public TransferState $state,
        public ?string $gatewayReference = null,
        public ?int $amountKobo = null,
        public ?string $currency = null,
        public ?string $message = null,
        public array $raw = [],
    ) {
    }
}
