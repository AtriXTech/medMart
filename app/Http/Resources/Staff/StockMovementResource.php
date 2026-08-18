<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
            'batch_id' => $this->batch_id,
            'staff' => $this->whenLoaded('staff', fn () => $this->staff->name),
            'created_at' => $this->created_at,
        ];
    }
}
