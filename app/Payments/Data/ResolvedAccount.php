<?php

declare(strict_types=1);

namespace App\Payments\Data;

final readonly class ResolvedAccount
{
    public function __construct(
        public string $accountName,
        public string $accountNumber,
    ) {
    }
}
