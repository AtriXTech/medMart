<?php

declare(strict_types=1);

namespace App\Settlement\Http\Resources;

use App\Settlement\Enums\SettlementStatus;
use App\Settlement\Support\Money;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SettlementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $admin = $request->user()?->is_super_admin === true;

        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'pharmacy_id' => $this->pharmacy_id,
            'pharmacy' => $this->whenLoaded('pharmacy', fn (): ?array => $this->pharmacy === null ? null : [
                'id' => $this->pharmacy->id,
                'name' => $this->pharmacy->name,
            ]),
            'status' => $this->status instanceof SettlementStatus ? $this->status->value : $this->status,
            'gross_kobo' => (int) $this->gross_kobo,
            'gross' => Money::toNaira((int) $this->gross_kobo),
            'fee_kobo' => (int) $this->fee_kobo,
            'fee' => Money::toNaira((int) $this->fee_kobo),
            'adjustment_kobo' => (int) $this->adjustment_kobo,
            'adjustment' => Money::toNaira((int) $this->adjustment_kobo),
            'net_kobo' => (int) $this->net_kobo,
            'net' => Money::toNaira((int) $this->net_kobo),
            'failure_reason' => $this->failure_reason,
            'hold_reason' => $this->hold_reason,
            'attempts' => (int) $this->attempts,
            'gateway_reference' => $this->when($admin, $this->gateway_reference),
            'processed_at' => $this->processed_at?->toIso8601String(),
            'initiated_at' => $this->initiated_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'account' => $this->whenLoaded('settlementAccount', function () use ($admin): ?array {
                $account = $this->settlementAccount;

                if ($account === null) {
                    return null;
                }

                $number = (string) $account->account_number;

                return [
                    'bank_name' => $account->bank_name,
                    'account_name' => $account->account_name,
                    'account_number' => $admin ? $number : str_repeat('*', max(0, strlen($number) - 4)) . substr($number, -4),
                ];
            }),
            'payments' => $this->whenLoaded('payments', fn (): array => $this->payments->map(static fn ($payment): array => [
                'id' => $payment->id,
                'order_id' => $payment->order_id,
                'reference' => $payment->reference,
                'gross_kobo' => (int) $payment->gross_kobo,
                'platform_fee_kobo' => (int) $payment->platform_fee_kobo,
                'gateway_fee_kobo' => (int) $payment->gateway_fee_kobo,
                'net_kobo' => (int) $payment->net_kobo,
                'paid_at' => $payment->paid_at?->toIso8601String(),
            ])->all()),
        ];
    }
}
