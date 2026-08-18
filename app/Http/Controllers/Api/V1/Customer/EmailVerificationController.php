<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ResendVerificationRequest;
use App\Http\Requests\Customer\VerifyEmailRequest;
use App\Models\Customer;
use App\Services\Auth\CustomerRegistrationService;
use Illuminate\Http\JsonResponse;

class EmailVerificationController extends Controller
{
    public function __construct(private readonly CustomerRegistrationService $registrationService)
    {
    }

    public function verify(VerifyEmailRequest $request): JsonResponse
    {
        $this->registrationService->verifyEmail(
            $request->string('email')->toString(),
            $request->string('token')->toString()
        );

        return response()->json(['message' => 'Email verified successfully. You can now log in.']);
    }

    public function resend(ResendVerificationRequest $request): JsonResponse
    {
        $customer = Customer::where('email', $request->string('email')->toString())->firstOrFail();

        if ($customer->email_verified_at !== null) {
            return response()->json(['message' => 'This email is already verified.']);
        }

        $this->registrationService->sendVerificationEmail($customer);

        return response()->json(['message' => 'Verification email sent.']);
    }
}
