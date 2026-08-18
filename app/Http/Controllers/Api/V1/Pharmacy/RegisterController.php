<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Pharmacy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\RegisterPharmacyRequest;
use App\Services\Pharmacy\PharmacyRegistrationService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly PharmacyRegistrationService $registrationService)
    {
    }

    public function register(RegisterPharmacyRequest $request): JsonResponse
    {
        $result = $this->registrationService->register($request->validated());

        return response()->json($result, 201);
    }
}