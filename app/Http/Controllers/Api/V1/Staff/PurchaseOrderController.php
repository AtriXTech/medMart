<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\ReceivePurchaseOrderItemsRequest;
use App\Http\Requests\Staff\StorePurchaseOrderRequest;
use App\Http\Resources\Staff\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Services\Purchasing\PurchasingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PurchaseOrderController extends Controller
{
    public function __construct(private readonly PurchasingService $purchasingService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $purchaseOrders = PurchaseOrder::query()
            ->with(['supplier', 'placedBy'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->latest()
            ->paginate(20);

        return PurchaseOrderResource::collection($purchaseOrders);
    }

    public function store(StorePurchaseOrderRequest $request): JsonResponse
    {
        $supplier = Supplier::findOrFail($request->validated('supplier_id'));

        $purchaseOrder = $this->purchasingService->createPurchaseOrder(
            $supplier,
            $request->validated(),
            $request->user()
        );

        return response()->json(new PurchaseOrderResource($purchaseOrder), 201);
    }

    public function show(PurchaseOrder $purchaseOrder): JsonResponse
    {
        return response()->json(
            new PurchaseOrderResource($purchaseOrder->load('items.product', 'supplier', 'placedBy'))
        );
    }

    public function receive(ReceivePurchaseOrderItemsRequest $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder = $this->purchasingService->receiveItems(
            $purchaseOrder,
            $request->validated('items'),
            $request->user()
        );

        return response()->json(new PurchaseOrderResource($purchaseOrder));
    }

    public function cancel(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder = $this->purchasingService->cancel($purchaseOrder);

        return response()->json(
            new PurchaseOrderResource($purchaseOrder->load('items.product', 'supplier', 'placedBy'))
        );
    }
}
