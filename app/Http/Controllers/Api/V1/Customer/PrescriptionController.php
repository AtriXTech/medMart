<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UploadPrescriptionRequest;
use App\Http\Resources\Customer\PrescriptionResource;
use App\Models\Prescription;
use App\Services\Prescriptions\PrescriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PrescriptionController extends Controller
{
    public function __construct(private readonly PrescriptionService $prescriptionService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $prescriptions = Prescription::where('customer_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return PrescriptionResource::collection($prescriptions);
    }

    public function store(UploadPrescriptionRequest $request): JsonResponse
    {
        $pharmacy = $request->user()->activePharmacy();

        $prescription = $this->prescriptionService->upload($request->user(), $pharmacy, $request->file('file'));

        return response()->json(new PrescriptionResource($prescription), 201);
    }
}
