<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->name,
                'price' => $this->product->price,
                'requires_prescription' => $this->product->requires_prescription,
            ],
            'quantity' => $this->quantity,
            'line_total' => round($this->product->price * $this->quantity, 2),
        ];
    }
}
