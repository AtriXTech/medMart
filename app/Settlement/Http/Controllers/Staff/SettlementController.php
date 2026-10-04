<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Settlement\Http\Controllers\Concerns\ResolvesPharmacy;
use App\Settlement\Http\Requests\ListSettlementsRequest;
use App\Settlement\Http\Resources\SettlementResource;
use App\Settlement\Services\SettlementQueryService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SettlementController extends Controller
{
    use ResolvesPharmacy;

    public function __construct(private readonly SettlementQueryService $settlements)
    {
    }

    public function index(ListSettlementsRequest $request): AnonymousResourceCollection
    {
        return SettlementResource::collection(
            $this->settlements->paginate($request->validated(), $this->pharmacyId($request))
        );
    }

    public function show(Request $request, int $settlement): SettlementResource
    {
        return new SettlementResource($this->settlements->find($settlement, $this->pharmacyId($request)));
    }
}
