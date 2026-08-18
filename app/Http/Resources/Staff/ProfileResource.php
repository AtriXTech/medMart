<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role instanceof \App\Enums\StaffRole ? $this->role->value : $this->role,
            'status' => $this->status,
            'pharmacy' => $this->whenLoaded('pharmacy', fn () => [
                'id' => $this->pharmacy->id,
                'name' => $this->pharmacy->name,
                'email' => $this->pharmacy->email,
                'phone' => $this->pharmacy->phone,
            ]),
            'staffRole' => $this->whenLoaded('staffRole', fn () => [
                'id' => $this->staffRole->id,
                'name' => $this->staffRole->name,
                'permissions' => $this->staffRole->permissions,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}