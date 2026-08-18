<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enums\PharmacyStatus;
use App\Exceptions\PharmacyAccessSuspendedException;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\NewAccessToken;

class StaffAuthService
{
    public function login(string $email, string $password, string $deviceName): NewAccessToken
    {
        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['These credentials do not match our records.'],
            ]);
        }

        if ($user->pharmacy->status === PharmacyStatus::Suspended) {
            throw new PharmacyAccessSuspendedException();
        }

        $user->forceFill(['last_login_at' => now()])->save();

        return $user->createToken($deviceName);
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function sendResetLink(string $email): string
    {
        return Password::broker('users')->sendResetLink(['email' => $email]);
    }

    public function resetPassword(string $token, string $email, string $password): string
    {
        return Password::broker('users')->reset(
            [
                'token' => $token,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password,
            ],
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
                event(new PasswordReset($user));
            }
        );
    }
}
