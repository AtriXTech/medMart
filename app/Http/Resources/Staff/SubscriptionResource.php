<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plan' => $this->whenLoaded('plan', fn () => [
                'id' => $this->plan->id,
                'name' => $this->plan->name,
                'price' => $this->plan->price,
                'billing_interval' => $this->plan->billing_interval,
                'max_branches' => $this->plan->max_branches,
                'max_staff' => $this->plan->max_staff,
                'max_products' => $this->plan->max_products,
            ]),
            'status' => $this->status,
            'current_period_starts_at' => $this->current_period_starts_at,
            'current_period_ends_at' => $this->current_period_ends_at,
            'cancelled_at' => $this->cancelled_at,
        ];
    }
}