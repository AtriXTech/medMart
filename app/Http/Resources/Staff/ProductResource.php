<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'generic_name' => $this->generic_name,
            'description' => $this->description,
            'barcode' => $this->barcode,
            'image_url' => $this->image_url ? Storage::url($this->image_url) : null,
            'requires_prescription' => $this->requires_prescription,
            'price' => $this->price,
            'reorder_level' => $this->reorder_level,
            'stock_quantity' => $this->stock_quantity,
            'is_available' => $this->is_available,
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}