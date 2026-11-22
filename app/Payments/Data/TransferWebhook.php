<?php

declare(strict_types=1);

namespace App\Payments\Data;

final readonly class TransferWebhook
{
    public function __construct(
        public string $reference,
        public ?TransferResult $result = null,
    ) {
    }
}
