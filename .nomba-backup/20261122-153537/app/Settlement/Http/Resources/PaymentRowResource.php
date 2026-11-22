<?php

declare(strict_types=1);

namespace App\Settlement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

final class PaymentRowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $eligibleAt = $this->getAttribute('eligible_at');
        $refundedAt = $this->getAttribute('refunded_at');

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'reference' => $this->reference,
            'pharmacy_id' => $this->pharmacy_id,
            'pharmacy' => $this->whenLoaded('pharmacy', fn (): ?array => $this->pharmacy === null ? null : [
                'id' => $this->pharmacy->id,
                'name' => $this->pharmacy->name,
            ]),
            'payment_status' => $this->status instanceof \BackedEnum ? $this->status->value : (string) $this->status,
            'settlement_status' => $this->getAttribute('settlement_status'),
            'gross_kobo' => (int) $this->getAttribute('gross_kobo'),
            'platform_fee_kobo' => (int) $this->getAttribute('platform_fee_kobo'),
            'gateway_fee_kobo' => (int) $this->getAttribute('gateway_fee_kobo'),
            'net_kobo' => (int) $this->getAttribute('net_kobo'),
            'hold_reason' => $this->getAttribute('hold_reason'),
            'eligible_at' => $eligibleAt !== null ? Carbon::parse($eligibleAt)->toIso8601String() : null,
            'refunded_at' => $refundedAt !== null ? Carbon::parse($refundedAt)->toIso8601String() : null,
            'paid_at' => $this->paid_at?->toIso8601String(),
        ];
    }
}
