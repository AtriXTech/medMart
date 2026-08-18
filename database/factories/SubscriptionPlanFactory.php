<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BillingInterval;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubscriptionPlanFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['Starter', 'Growth', 'Pro']);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => fake()->randomElement([5000, 15000, 35000]),
            'billing_interval' => BillingInterval::Monthly,
            'max_branches' => fake()->numberBetween(1, 5),
            'max_staff' => fake()->numberBetween(3, 20),
            'max_products' => fake()->numberBetween(100, 5000),
            'is_active' => true,
        ];
    }
}
