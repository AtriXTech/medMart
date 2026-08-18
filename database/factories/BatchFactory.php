<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pharmacy;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class BatchFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pharmacy_id' => Pharmacy::factory(),
            'product_id' => Product::factory(),
            'batch_number' => strtoupper(fake()->bothify('BATCH-####??')),
            'expiry_date' => fake()->dateTimeBetween('+3 months', '+2 years'),
            'quantity' => fake()->numberBetween(50, 500),
            'cost_price' => fake()->randomFloat(2, 100, 3000),
        ];
    }
}
