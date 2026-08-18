<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateSaleRequest;
use App\Http\Resources\Staff\SaleResource;
use App\Models\Sale;
use App\Services\Sales\PosSaleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SaleController extends Controller
{
    public function __construct(private readonly PosSaleService $posSaleService)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return SaleResource::collection(
            Sale::query()
                ->with('cashier')
                ->withCount('items')
                ->latest()
                ->paginate(20)
        );
    }

    public function store(CreateSaleRequest $request): JsonResponse
    {
        $sale = $this->posSaleService->createSale($request->validated(), $request->user());

        return response()->json(new SaleResource($sale->load('items.product', 'cashier')), 201);
    }

    public function show(Sale $sale): JsonResponse
    {
        return response()->json(new SaleResource($sale->load('items.product', 'cashier')));
    }
}