<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Mail\VerifyCustomerEmailMail;
use App\Models\Customer;
use App\Services\Pharmacy\PharmacyCodeService;
use App\Services\Pharmacy\PharmacyLinkService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerRegistrationService
{
    public function __construct(
        private readonly PharmacyCodeService $pharmacyCodeService,
        private readonly PharmacyLinkService $pharmacyLinkService,
    ) {
    }

    public function register(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $pharmacyCode = $this->pharmacyCodeService->validateCode($data['pharmacy_code']);

            $customer = Customer::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $this->pharmacyLinkService->linkCustomerToPharmacy($customer, $pharmacyCode);

            $this->sendVerificationEmail($customer);

            return $customer;
        });
    }

    public function sendVerificationEmail(Customer $customer): void
    {
        $rawToken = Str::random(60);

        $customer->forceFill([
            'email_verification_token' => hash('sha256', $rawToken),
            'email_verification_token_expires_at' => now()->addHours(24),
        ])->save();

        $verificationUrl = config('app.frontend_url')
            . '/verify-email?token=' . $rawToken
            . '&email=' . urlencode($customer->email);

        Mail::to($customer->email)->send(new VerifyCustomerEmailMail($customer, $verificationUrl));
    }

    public function verifyEmail(string $email, string $token): Customer
    {
        $customer = Customer::where('email', $email)->firstOrFail();

        $hashedToken = hash('sha256', $token);

        if (
            $customer->email_verification_token !== $hashedToken
            || $customer->email_verification_token_expires_at === null
            || $customer->email_verification_token_expires_at->isPast()
        ) {
            throw ValidationException::withMessages([
                'token' => ['This verification link is invalid or has expired.'],
            ]);
        }

        $customer->forceFill([
            'email_verified_at' => now(),
            'email_verification_token' => null,
            'email_verification_token_expires_at' => null,
        ])->save();

        return $customer;
    }
}
