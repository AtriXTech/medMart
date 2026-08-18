<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PharmacyStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PharmacyFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 9999),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->phoneNumber(),
            'status' => PharmacyStatus::Active,
            'timezone' => 'Africa/Lagos',
            'currency' => 'NGN',
        ];
    }
}
