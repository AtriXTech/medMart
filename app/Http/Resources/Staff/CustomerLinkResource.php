<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer->id,
                'username' => $this->customer->username,
                'name' => $this->customer->name,
                'email' => $this->customer->email,
                'email_verified' => $this->customer->email_verified_at !== null,
            ]),
            'is_active' => $this->is_active,
            'is_suspended' => $this->is_suspended,
            'linked_at' => $this->created_at,
        ];
    }
}
