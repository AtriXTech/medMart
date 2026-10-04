<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Models\LedgerEntry;

final class LedgerService
{
    public function post(
        int|string $pharmacyId,
        LedgerEntryType $type,
        int $amountKobo,
        string $idempotencyKey,
        array $refs = [],
        array $meta = [],
    ): LedgerEntry {
        return LedgerEntry::query()->firstOrCreate(
            ['idempotency_key' => $idempotencyKey],
            [
                'pharmacy_id' => (int) $pharmacyId,
                'type' => $type,
                'amount_kobo' => $amountKobo,
                'payment_id' => $refs['payment_id'] ?? null,
                'settlement_id' => $refs['settlement_id'] ?? null,
                'order_id' => $refs['order_id'] ?? null,
                'meta' => $meta === [] ? null : $meta,
            ]
        );
    }

    public function balance(int $pharmacyId): int
    {
        return (int) LedgerEntry::query()->where('pharmacy_id', $pharmacyId)->sum('amount_kobo');
    }
}
