<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function initiate(Request $request, Order $order): JsonResponse
    {
        abort_if($order->customer_id !== $request->user()->id, 404);

        $result = $this->paymentService->initiate($order);

        return response()->json($result);
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => ['required', 'string'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
        ]);

        $order = Order::findOrFail($request->integer('order_id'));
        abort_if($order->customer_id !== $request->user()->id, 404);

        $result = $this->paymentService->verifyPayment($order, $request->string('reference')->toString());

        return response()->json($result);
    }
}