<?php

declare(strict_types=1);

namespace App\Http\Resources\Staff;

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
            'barcode' => $this->barcode,
            'image_url' => $this->resolveImageUrl(),
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

    private function resolveImageUrl(): ?string
    {
        $path = $this->image_url;
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($path, 'storage/')) {
            return '/' . $path;
        }

        return '/storage/' . $path;
    }
}