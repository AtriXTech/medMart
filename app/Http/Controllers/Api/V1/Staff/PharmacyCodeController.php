<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\GeneratePharmacyCodeRequest;
use App\Http\Resources\Staff\PharmacyCodeResource;
use App\Models\PharmacyCode;
use App\Services\Pharmacy\PharmacyCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PharmacyCodeController extends Controller
{
    public function __construct(private readonly PharmacyCodeService $pharmacyCodeService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return PharmacyCodeResource::collection(
            PharmacyCode::latest()->paginate(20)
        );
    }

    public function store(GeneratePharmacyCodeRequest $request): JsonResponse
    {
        $pharmacyCode = $this->pharmacyCodeService->generate(
            $request->user()->pharmacy,
            $request->validated(),
            $request->user()
        );

        return response()->json(new PharmacyCodeResource($pharmacyCode), 201);
    }
}
