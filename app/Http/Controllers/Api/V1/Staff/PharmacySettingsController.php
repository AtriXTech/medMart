<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\PharmacySettingsResource;
use App\Http\Requests\Staff\UpdatePharmacySettingsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PharmacySettingsController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json(new PharmacySettingsResource($request->user()->pharmacy));
    }

    public function update(UpdatePharmacySettingsRequest $request): JsonResponse
    {
        $pharmacy = $request->user()->pharmacy;
        $pharmacy->update($request->validated());

        return response()->json(new PharmacySettingsResource($pharmacy));
    }
}