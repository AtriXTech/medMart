<?php

declare(strict_types=1);

namespace App\Http\Resources\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'description' => $this->description,
            'requires_prescription' => $this->requires_prescription,
            'price' => $this->price,
            'in_stock' => $this->is_available && $this->stock_quantity > 0,
            'category' => $this->whenLoaded('category', fn () => $this->category?->name),
        ];
    }
}
