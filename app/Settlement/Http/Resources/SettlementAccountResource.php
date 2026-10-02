<?php

declare(strict_types=1);

namespace App\Settlement\Http\Resources;

use App\Settlement\Enums\AccountStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SettlementAccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $admin = $request->user()?->is_super_admin === true;
        $number = (string) $this->account_number;

        return [
            'id' => $this->id,
            'pharmacy_id' => $this->when($admin, $this->pharmacy_id),
            'pharmacy' => $this->when(
                $admin && $this->resource->relationLoaded('pharmacy') && $this->pharmacy !== null,
                fn (): array => ['id' => $this->pharmacy->id, 'name' => $this->pharmacy->name]
            ),
            'bank_id' => $this->bank_id,
            'bank_name' => $this->bank_name,
            'account_number' => $admin ? $number : $this->mask($number),
            'account_name' => $this->account_name,
            'status' => $this->status instanceof AccountStatus ? $this->status->value : $this->status,
            'rejection_reason' => $this->rejection_reason,
            'name_match_score' => $this->name_match_score,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    private function mask(string $number): string
    {
        $length = strlen($number);

        return $length <= 4 ? $number : str_repeat('*', $length - 4) . substr($number, -4);
    }
}
