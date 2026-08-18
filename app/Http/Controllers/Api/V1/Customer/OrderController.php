<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CancelOrderRequest;
use App\Http\Resources\Customer\OrderResource;
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
        $orders = Order::where('customer_id', $request->user()->id)
            ->when(
                $request->filled('pharmacy_id'),
                fn ($query) => $query->where('pharmacy_id', $request->integer('pharmacy_id'))
            )
            ->with('items.product')
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        abort_if($order->customer_id !== $request->user()->id, 404);

        return response()->json(new OrderResource($order->load('items.product')));
    }

    public function cancel(CancelOrderRequest $request, Order $order): JsonResponse
    {
        abort_if($order->customer_id !== $request->user()->id, 404);

        abort_unless(
            in_array($order->status, [OrderStatus::PendingPayment, OrderStatus::Paid], true),
            422,
            'This order can no longer be cancelled.'
        );

        $order = $this->orderService->transitionTo(
            $order,
            OrderStatus::Cancelled,
            null,
            $request->string('reason')->toString()
        );

        return response()->json(new OrderResource($order));
    }
}
