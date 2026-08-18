<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ForgotPasswordRequest;
use App\Http\Requests\Customer\LoginRequest;
use App\Http\Requests\Customer\RegisterRequest;
use App\Http\Requests\Customer\ResetPasswordRequest;
use App\Http\Resources\Customer\AuthenticatedCustomerResource;
use App\Services\Auth\CustomerAuthService;
use App\Services\Auth\CustomerRegistrationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(
        private readonly CustomerAuthService $authService,
        private readonly CustomerRegistrationService $registrationService,
    ) {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $customer = $this->registrationService->register($request->validated());

        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.',
            'customer' => new AuthenticatedCustomerResource($customer),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $token = $this->authService->login(
            $request->string('email')->toString(),
            $request->string('password')->toString(),
            $request->string('device_name')->toString(),
        );

        return response()->json([
            'token' => $token->plainTextToken,
            'customer' => new AuthenticatedCustomerResource($token->accessToken->tokenable),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json(['message' => 'Logged out.']);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->sendResetLink($request->string('email')->toString());

        return response()->json(
            ['message' => __($status)],
            $status === Password::RESET_LINK_SENT ? 200 : 422
        );
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword(
            $request->string('token')->toString(),
            $request->string('email')->toString(),
            $request->string('password')->toString(),
        );

        return response()->json(
            ['message' => __($status)],
            $status === Password::PASSWORD_RESET ? 200 : 422
        );
    }
}
