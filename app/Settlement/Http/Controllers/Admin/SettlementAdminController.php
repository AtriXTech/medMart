<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settlement\Http\Requests\ListSettlementsRequest;
use App\Settlement\Http\Requests\ReasonRequest;
use App\Settlement\Http\Resources\SettlementResource;
use App\Settlement\Services\SettlementAdminService;
use App\Settlement\Services\SettlementQueryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SettlementAdminController extends Controller
{
    public function __construct(
        private readonly SettlementQueryService $settlements,
        private readonly SettlementAdminService $admin,
    ) {
    }

    public function index(ListSettlementsRequest $request): AnonymousResourceCollection
    {
        return SettlementResource::collection($this->settlements->paginate($request->validated()));
    }

    public function show(int $settlement): SettlementResource
    {
        return new SettlementResource($this->settlements->find($settlement));
    }

    public function retry(Request $request, int $settlement): JsonResponse
    {
        $outcome = $this->admin->retry($settlement, $request->user()->getKey());

        return response()->json(['message' => 'Retry requested.', 'outcome' => $outcome]);
    }

    public function hold(ReasonRequest $request, int $settlement): JsonResponse
    {
        $this->admin->hold($settlement, (string) $request->validated('reason'), (int) $request->user()->getKey());

        return response()->json(['message' => 'Settlement placed on hold.']);
    }

    public function release(Request $request, int $settlement): JsonResponse
    {
        $this->admin->release($settlement, (int) $request->user()->getKey());

        return response()->json(['message' => 'Settlement released.']);
    }

    public function cancel(ReasonRequest $request, int $settlement): JsonResponse
    {
        $this->admin->cancel($settlement, (string) $request->validated('reason'), (int) $request->user()->getKey());

        return response()->json(['message' => 'Settlement cancelled. Funds returned to the pharmacy balance.']);
    }

    public function run(Request $request): JsonResponse
    {
        $data = $request->validate([
            'pharmacy_id' => ['sometimes', 'nullable', 'integer', 'exists:pharmacies,id'],
        ]);

        $result = $this->admin->runBatch(
            isset($data['pharmacy_id']) ? (int) $data['pharmacy_id'] : null,
            (int) $request->user()->getKey()
        );

        return response()->json(['message' => 'Settlement run completed.', 'data' => $result]);
    }
}
