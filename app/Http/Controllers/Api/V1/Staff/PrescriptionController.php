<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\ReviewPrescriptionRequest;
use App\Http\Resources\Staff\PrescriptionResource;
use App\Models\Prescription;
use App\Services\Prescriptions\PrescriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrescriptionController extends Controller
{
    public function __construct(private readonly PrescriptionService $prescriptionService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $prescriptions = Prescription::query()
            ->with('customer')
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->latest()
            ->paginate(20);

        return PrescriptionResource::collection($prescriptions);
    }

    public function show(Prescription $prescription): JsonResponse
    {
        return response()->json(new PrescriptionResource($prescription->load(['customer', 'reviewedBy'])));
    }

    public function review(ReviewPrescriptionRequest $request, Prescription $prescription): JsonResponse
    {
        if ($request->string('status')->toString() === 'approved') {
            $prescription = $this->prescriptionService->approve($prescription, $request->user());
        } else {
            $prescription = $this->prescriptionService->reject(
                $prescription,
                $request->user(),
                $request->string('rejection_reason')->toString()
            );
        }

        return response()->json(new PrescriptionResource($prescription->load(['customer', 'reviewedBy'])));
    }

    public function downloadFile(Prescription $prescription): StreamedResponse
    {
        return Storage::disk('local')->response($prescription->file_path, $prescription->original_filename);
    }
}
