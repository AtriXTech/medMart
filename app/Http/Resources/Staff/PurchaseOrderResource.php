<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'expected_date' => $this->expected_date?->toDateString(),
            'notes' => $this->notes,
            'placed_by' => $this->whenLoaded('placedBy', fn () => $this->placedBy?->name),
            'items' => PurchaseOrderItemResource::collection($this->whenLoaded('items')),
            'created_at' => $this->created_at,
        ];
    }
}