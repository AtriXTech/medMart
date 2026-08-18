<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\StaffRole;
use App\Models\Pharmacy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pharmacy_id' => Pharmacy::factory(),
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => StaffRole::Owner,
            'status' => 'active',
        ];
    }

    public function role(StaffRole $role): static
    {
        return $this->state(fn (array $attributes) => ['role' => $role]);
    }
}
