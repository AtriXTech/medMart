<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\UpdateDeliveryStatusRequest;
use App\Http\Requests\Staff\UpdateOrderStatusRequest;
use App\Http\Resources\Staff\OrderResource;
use App\Models\Order;
use App\Services\Orders\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = Order::query()
            ->with(['items.product', 'customer'])
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->string('status')->toString())
            )
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json(new OrderResource($order->load(['items.product', 'customer'])));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): JsonResponse
    {
        $order = $this->orderService->transitionTo(
            $order,
            OrderStatus::from($request->string('status')->toString()),
            $request->user(),
            $request->input('reason')
        );

        return response()->json(new OrderResource($order->load(['items.product', 'customer'])));
    }

    public function updateDeliveryStatus(UpdateDeliveryStatusRequest $request, Order $order): JsonResponse
    {
        abort_if($order->fulfillment_type !== FulfillmentType::Delivery, 422, 'This order is not a delivery order.');

        $order->update(['delivery_status' => $request->string('delivery_status')->toString()]);

        return response()->json(new OrderResource($order->load(['items.product', 'customer'])));
    }
}
