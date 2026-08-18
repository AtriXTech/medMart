<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'pharmacy_id' => $this->pharmacy_id,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'fulfillment_type' => $this->fulfillment_type,
            'delivery_address' => $this->delivery_address,
            'delivery_status' => $this->delivery_status,
            'ready_at' => $this->ready_at,
            'cancelled_at' => $this->cancelled_at,
            'cancellation_reason' => $this->cancellation_reason,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}
