<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $total = $this->items->sum(fn ($item) => $item->product->price * $item->quantity);

        return [
            'id' => $this->id,
            'pharmacy_id' => $this->pharmacy_id,
            'items' => CartItemResource::collection($this->items),
            'total' => round($total, 2),
        ];
    }
}
