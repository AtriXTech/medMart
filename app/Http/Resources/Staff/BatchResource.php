<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_number' => $this->batch_number,
            'expiry_date' => $this->expiry_date?->toDateString(),
            'quantity' => $this->quantity,
            'cost_price' => $this->cost_price,
        ];
    }
}
