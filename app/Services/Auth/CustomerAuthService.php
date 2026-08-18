<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\Customer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

class CustomerAuthService
{
    public function login(string $email, string $password, string $deviceName): NewAccessToken
    {
        $customer = Customer::where('email', $email)->first();

        if (! $customer || ! Hash::check($password, $customer->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($customer->email_verified_at === null) {
            throw ValidationException::withMessages([
                'email' => ['Please verify your email before logging in.'],
            ]);
        }

        return $customer->createToken($deviceName);
    }

    public function logout(Customer $customer): void
    {
        $customer->currentAccessToken()->delete();
    }

    public function sendResetLink(string $email): string
    {
        return Password::broker('customers')->sendResetLink(['email' => $email]);
    }

    public function resetPassword(string $token, string $email, string $password): string
    {
        return Password::broker('customers')->reset(
            [
                'token' => $token,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
            ],
            function (Customer $customer, string $password) {
                $customer->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($customer));
            }
        );
    }
}
