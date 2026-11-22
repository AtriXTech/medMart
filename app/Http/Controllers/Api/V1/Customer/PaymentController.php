<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
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
            'reference' => ['required_without:orderReference', 'nullable', 'string', 'max:255'],
            'orderReference' => ['nullable', 'string', 'max:255'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
        ]);

        $reference = $request->string('reference')->toString() ?: $request->string('orderReference')->toString();

        $order = $request->filled('order_id')
            ? Order::query()->find($request->integer('order_id'))
            : $this->orderForReference($reference);

        abort_if($order === null || $order->customer_id !== $request->user()->id, 404);

        return response()->json($this->paymentService->verifyPayment($order, $reference));
    }

    private function orderForReference(string $reference): ?Order
    {
        $payment = Payment::query()
            ->withoutGlobalScopes()
            ->where(function ($query) use ($reference): void {
                $query->where('reference', $reference)->orWhere('gateway_reference', $reference);
            })
            ->first();

        return $payment === null ? null : Order::query()->find($payment->order_id);
    }
}
