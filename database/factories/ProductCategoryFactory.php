<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pharmacy_id' => Pharmacy::factory(),
            'name' => fake()->unique()->words(2, true),
        ];
    }
}
