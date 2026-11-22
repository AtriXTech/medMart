<?php

declare(strict_types=1);

namespace App\Services\Payments;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Payments\Enums\ChargeState;
use App\Payments\GatewayManager;
use App\Services\Orders\OrderService;
use App\Settlement\Jobs\RecordPaymentSettlementJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        private readonly GatewayManager $gateways,
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

        $gateway = $this->gateways->collections();
        $reference = 'MEDMART-' . $order->id . '-' . Str::random(10);

        Payment::create([
            'pharmacy_id' => $order->pharmacy_id,
            'order_id' => $order->id,
            'reference' => $reference,
            'gateway' => $gateway->gateway()->value,
            'status' => PaymentStatus::Unpaid,
            'amount' => $order->total,
        ]);

        $session = $gateway->initializeCharge(
            $reference,
            (int) round($order->total * 100),
            $order->customer->email,
            [
                'order_id' => $order->id,
                'payment_type' => 'order',
            ],
            $gateway->customerCallbackUrl()
        );

        return [
            'authorization_url' => $session->authorizationUrl,
            'reference' => $reference,
        ];
    }

    public function verifyPayment(Order $order, string $reference): array
    {
        $payment = Payment::where('order_id', $order->id)
            ->where(function ($query) use ($reference): void {
                $query->where('reference', $reference)->orWhere('gateway_reference', $reference);
            })
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
            $this->handleSuccessfulCharge($payment->reference);

            $payment->refresh();

            if ($payment->status === PaymentStatus::Paid) {
                return [
                    'status' => 'success',
                    'message' => 'Payment verified successfully',
                    'order' => $order->fresh()->load('items.product'),
                ];
            }

            if ($payment->status === PaymentStatus::Unpaid) {
                return [
                    'status' => 'pending',
                    'message' => 'Your payment is still being processed. Please check again shortly.',
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

            $charge = $this->gateways->for($payment->gateway)->verifyCharge($reference);

            if ($charge->state === ChargeState::Pending) {
                return null;
            }

            $amountMatches = $charge->amountKobo === (int) round($payment->amount * 100);
            $currencyMatches = $charge->currency === strtoupper((string) config('settlement.currency', 'NGN'));

            if (! $charge->isSuccessful() || ! $amountMatches || ! $currencyMatches) {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'gateway_response' => json_encode($charge->raw),
                ]);

                return null;
            }

            $payment->update([
                'status' => PaymentStatus::Paid,
                'gateway_reference' => $charge->gatewayReference ?? $reference,
                'gateway_response' => json_encode($charge->raw),
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
