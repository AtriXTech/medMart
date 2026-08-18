<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateProfileRequest;
use App\Http\Requests\Staff\ChangePasswordRequest;
use App\Http\Resources\Staff\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json(new ProfileResource($request->user()->load('pharmacy', 'staffRole')));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json(new ProfileResource($user->load('pharmacy', 'staffRole')));
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->string('new_password')->toString()),
        ]);

        return response()->json(['message' => 'Password changed successfully.']);
    }
}