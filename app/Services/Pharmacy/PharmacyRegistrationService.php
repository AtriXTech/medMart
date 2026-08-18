<?php

declare(strict_types=1);

namespace App\Services\Pharmacy;

use App\Enums\PharmacyStatus;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PharmacyRegistrationService
{
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $pharmacy = Pharmacy::create([
                'name' => $data['pharmacy_name'],
                'slug' => $this->generateUniqueSlug($data['pharmacy_name']),
                'email' => $data['email'],
                'phone' => $data['phone'],
                'status' => PharmacyStatus::Active,
                'timezone' => 'Africa/Lagos',
                'currency' => 'NGN',
                'is_test_account' => false,
                'settings' => [],
            ]);

            $user = User::create([
                'pharmacy_id' => $pharmacy->id,
                'name' => $data['owner_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'owner',
                'is_active' => true,
            ]);

            $token = $user->createToken('registration')->plainTextToken;

            return [
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'pharmacy' => [
                    'id' => $pharmacy->id,
                    'name' => $pharmacy->name,
                    'slug' => $pharmacy->slug,
                ],
            ];
        });
    }

    private function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Pharmacy::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}