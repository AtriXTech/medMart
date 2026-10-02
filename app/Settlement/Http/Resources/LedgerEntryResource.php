<?php

declare(strict_types=1);

namespace App\Settlement\Http\Resources;

use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class LedgerEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type instanceof LedgerEntryType ? $this->type->value : $this->type,
            'amount_kobo' => (int) $this->amount_kobo,
            'amount' => Money::toNaira((int) $this->amount_kobo),
            'payment_id' => $this->payment_id,
            'settlement_id' => $this->settlement_id,
            'order_id' => $this->order_id,
            'meta' => $this->meta,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
