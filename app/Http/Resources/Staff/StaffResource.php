<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'staff_role_id' => $this->staff_role_id,
            'staffRole' => $this->whenLoaded('staffRole', fn () => [
                'id' => $this->staffRole->id,
                'name' => $this->staffRole->name,
            ]),
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}