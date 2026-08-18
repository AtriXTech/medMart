<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyCodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'expires_at' => $this->expires_at,
            'max_uses' => $this->max_uses,
            'uses_count' => $this->uses_count,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
        ];
    }
}
