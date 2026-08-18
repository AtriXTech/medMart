<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pharmacy;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pharmacy_id' => Pharmacy::factory(),
            'product_category_id' => ProductCategory::factory(),
            'name' => fake()->words(3, true),
            'generic_name' => fake()->word(),
            'description' => fake()->sentence(),
            'barcode' => fake()->unique()->ean13(),
            'requires_prescription' => false,
            'price' => fake()->randomFloat(2, 200, 5000),
            'reorder_level' => 10,
            'stock_quantity' => 0,
            'is_available' => true,
        ];
    }
}
