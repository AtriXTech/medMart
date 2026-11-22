<?php

declare(strict_types=1);

namespace App\Payments\Data;

final readonly class TransferRequest
{
    public function __construct(
        public string $reference,
        public int $amountKobo,
        public string $narration,
        public PayoutDestination $destination,
    ) {
    }
}
