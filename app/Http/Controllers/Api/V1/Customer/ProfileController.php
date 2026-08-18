<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateCustomerProfileRequest;
use App\Http\Requests\Customer\ChangeCustomerPasswordRequest;
use App\Http\Resources\Customer\CustomerProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json(new CustomerProfileResource($request->user()));
    }

    public function update(UpdateCustomerProfileRequest $request): JsonResponse
    {
        $customer = $request->user();
        $customer->update($request->validated());

        return response()->json(new CustomerProfileResource($customer));
    }

    public function changePassword(ChangeCustomerPasswordRequest $request): JsonResponse
    {
        $customer = $request->user();
        $customer->update([
            'password' => Hash::make($request->string('new_password')->toString()),
        ]);

        return response()->json(['message' => 'Password changed successfully.']);
    }
}