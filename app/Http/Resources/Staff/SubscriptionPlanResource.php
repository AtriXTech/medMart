<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $allowedDurations = $this->allowed_durations;
        
        if (is_string($allowedDurations)) {
            $allowedDurations = json_decode($allowedDurations, true);
        }
        
        if (!is_array($allowedDurations)) {
            $allowedDurations = [1, 6, 12, 24];
        }
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'billing_interval' => $this->billing_interval,
            'max_branches' => $this->max_branches,
            'max_staff' => $this->max_staff,
            'max_products' => $this->max_products,
            'allowed_durations' => $allowedDurations,
        ];
    }
}