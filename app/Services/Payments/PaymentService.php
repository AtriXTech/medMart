<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Orders\OrderService;
use App\Settlement\Jobs\RecordPaymentSettlementJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        private readonly PaystackService $paystackService,
        private readonly OrderService $orderService,
    ) {
    }

    public function initiate(Order $order): array
    {
        if ($order->status !== OrderStatus::PendingPayment) {
            throw ValidationException::withMessages([
                'order' => ['This order is not awaiting payment.'],
            ]);
        }

        $reference = 'MEDMART-' . $order->id . '-' . Str::random(10);

        Payment::create([
            'pharmacy_id' => $order->pharmacy_id,
            'order_id' => $order->id,
            'reference' => $reference,
            'status' => PaymentStatus::Unpaid,
            'amount' => $order->total,
        ]);

        $response = $this->paystackService->initializeTransaction(
            $reference,
            (int) round($order->total * 100),
            $order->customer->email,
            [
                'order_id' => $order->id,
                'payment_type' => 'order',
            ],
            config('services.paystack.customer_callback_url')
        );

        return [
            'authorization_url' => $response['data']['authorization_url'],
            'reference' => $reference,
        ];
    }

    public function verifyPayment(Order $order, string $reference): array
    {
        $payment = Payment::where('reference', $reference)
            ->where('order_id', $order->id)
            ->first();

        if (! $payment) {
            return [
                'status' => 'failed',
                'message' => 'Payment record not found.',
            ];
        }

        if ($payment->status === PaymentStatus::Paid) {
            return [
                'status' => 'success',
                'message' => 'Payment already verified',
                'order' => $order->fresh()->load('items.product'),
            ];
        }

        try {
            $this->handleSuccessfulCharge($reference);

            $payment->refresh();

            if ($payment->status === PaymentStatus::Paid) {
                return [
                    'status' => 'success',
                    'message' => 'Payment verified successfully',
                    'order' => $order->fresh()->load('items.product'),
                ];
            }

            return [
                'status' => 'failed',
                'message' => 'Payment verification failed',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Unable to verify payment: ' . $e->getMessage(),
            ];
        }
    }

    public function handleSuccessfulCharge(string $reference): void
    {
        $confirmed = DB::transaction(function () use ($reference): ?array {
            $payment = Payment::withoutGlobalScopes()->where('reference', $reference)->lockForUpdate()->first();

            if (! $payment || $payment->status === PaymentStatus::Paid) {
                return null;
            }

            $verification = $this->paystackService->verifyTransaction($reference);
            $verifiedData = $verification['data'] ?? [];

            $isSuccessful = ($verifiedData['status'] ?? null) === 'success';
            $amountMatches = (int) ($verifiedData['amount'] ?? 0) === (int) round($payment->amount * 100);

            if (! $isSuccessful || ! $amountMatches) {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'gateway_response' => json_encode($verifiedData),
                ]);

                return null;
            }

            $payment->update([
                'status' => PaymentStatus::Paid,
                'paystack_reference' => $verifiedData['reference'] ?? $reference,
                'gateway_response' => json_encode($verifiedData),
                'paid_at' => now(),
            ]);

            $order = Order::withoutGlobalScopes()->find($payment->order_id);

            if ($order !== null && $order->status === OrderStatus::PendingPayment) {
                $this->orderService->transitionTo($order, OrderStatus::Paid);
            }

            return ['payment_id' => (int) $payment->id, 'order_id' => (int) $payment->order_id];
        });

        if ($confirmed === null) {
            return;
        }

        $this->advanceToReceived($confirmed['order_id']);
        $this->queueSettlementRecording($confirmed['payment_id']);
    }

    private function advanceToReceived(int $orderId): void
    {
        try {
            $order = Order::withoutGlobalScopes()->find($orderId);

            if ($order !== null && $order->status === OrderStatus::Paid) {
                $this->orderService->transitionTo($order, OrderStatus::Received);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    private function queueSettlementRecording(int $paymentId): void
    {
        try {
            RecordPaymentSettlementJob::dispatch($paymentId);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }
}
