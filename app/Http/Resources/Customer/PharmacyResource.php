<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PharmacyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->pharmacy->id,
            'name' => $this->pharmacy->name,
            'is_active' => $this->is_active,
            'linked_at' => $this->created_at,
        ];
    }
}
