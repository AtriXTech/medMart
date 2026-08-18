<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\JoinPharmacyRequest;
use App\Http\Requests\Customer\SwitchPharmacyRequest;
use App\Http\Resources\Customer\PharmacyResource;
use App\Models\Pharmacy;
use App\Services\Pharmacy\PharmacyCodeService;
use App\Services\Pharmacy\PharmacyLinkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PharmacyLinkController extends Controller
{
    public function __construct(
        private readonly PharmacyCodeService $pharmacyCodeService,
        private readonly PharmacyLinkService $pharmacyLinkService,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $links = $request->user()->pharmacyLinks()->with('pharmacy')->get();

        return PharmacyResource::collection($links);
    }

    public function join(JoinPharmacyRequest $request): JsonResponse
    {
        $pharmacyCode = $this->pharmacyCodeService->validateCode($request->string('pharmacy_code')->toString());

        $link = $this->pharmacyLinkService->linkCustomerToPharmacy($request->user(), $pharmacyCode);

        return response()->json(new PharmacyResource($link), 201);
    }

    public function switch(SwitchPharmacyRequest $request): JsonResponse
    {
        $pharmacy = Pharmacy::findOrFail($request->integer('pharmacy_id'));

        $link = $this->pharmacyLinkService->switchActivePharmacy($request->user(), $pharmacy);

        return response()->json(new PharmacyResource($link));
    }
}
