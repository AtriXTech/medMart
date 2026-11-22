<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Enums\BillingInterval;
use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Pharmacy;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Services\Payments\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PharmacySubscriptionService
{
    public function __construct(
        private readonly PaymentGatewayInterface $paymentGateway
    ) {
    }

    public function initiate(Pharmacy $pharmacy, SubscriptionPlan $plan, int $durationMonths = 1): array
    {
        $totalAmount = $plan->price * $durationMonths;
        $reference = 'MEDMART-SUB-' . $pharmacy->id . '-' . Str::random(10);

        $payment = SubscriptionPayment::create([
            'pharmacy_id' => $pharmacy->id,
            'subscription_plan_id' => $plan->id,
            'reference' => $reference,
            'status' => PaymentStatus::Unpaid,
            'amount' => $totalAmount,
            'gateway_response' => json_encode([
                'duration_months' => $durationMonths,
                'subscription_plan_id' => $plan->id,
                'pharmacy_id' => $pharmacy->id,
            ]),
        ]);

        $response = $this->paymentGateway->initializeTransaction(
            $reference,
            (int) round($totalAmount * 100),
            $pharmacy->email,
            [
                'pharmacy_id' => $pharmacy->id,
                'subscription_plan_id' => $plan->id,
                'duration_months' => $durationMonths,
                'payment_type' => 'subscription',
            ]
        );

        return [
            'authorization_url' => $response['data']['authorization_url'],
            'reference' => $reference,
            'amount' => $totalAmount,
        ];
    }

    public function verifyPayment(Pharmacy $pharmacy, string $reference): array
    {
        $payment = SubscriptionPayment::where('reference', $reference)
            ->where('pharmacy_id', $pharmacy->id)
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
                'subscription' => $pharmacy->subscription?->load('plan'),
            ];
        }

        try {
            $verification = $this->paymentGateway->verifyTransaction($reference);
            $verifiedData = $verification['data'] ?? [];

            if (! $this->isValidVerification($verifiedData, $payment)) {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'gateway_response' => json_encode(array_merge(
                        json_decode($payment->gateway_response ?? '{}', true),
                        $verifiedData
                    )),
                ]);

                Log::warning('Payment verification failed', [
                    'reference' => $reference,
                    'payment_id' => $payment->id,
                    'verified_data' => $verifiedData,
                ]);

                return [
                    'status' => 'failed',
                    'message' => 'Payment verification failed. Please contact support.',
                ];
            }

            $this->markPaymentAsPaid($payment, $verifiedData);

            return [
                'status' => 'success',
                'message' => 'Payment verified successfully',
                'subscription' => $payment->pharmacy->subscription?->load('plan'),
            ];
        } catch (\Exception $e) {
            Log::error('Payment verification error', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);

            return [
                'status' => 'failed',
                'message' => 'Unable to verify payment. Please try again.',
            ];
        }
    }

    public function handleWebhook(array $payload): void
    {
        $event = $payload['event'] ?? null;
        $data = $payload['data'] ?? [];

        if ($event !== 'charge.success') {
            return;
        }

        $reference = $data['reference'] ?? null;

        if (! $reference) {
            return;
        }

        $payment = SubscriptionPayment::where('reference', $reference)->first();

        if (! $payment || $payment->status === PaymentStatus::Paid) {
            return;
        }

        try {
            $this->verifyPayment($payment->pharmacy, $reference);
        } catch (\Exception $e) {
            Log::error('Failed to process webhook payment', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function isValidVerification(array $verifiedData, SubscriptionPayment $payment): bool
    {
        if (($verifiedData['status'] ?? null) !== 'success') {
            return false;
        }

        if ((int) ($verifiedData['amount'] ?? 0) !== (int) round($payment->amount * 100)) {
            return false;
        }

        if (($verifiedData['currency'] ?? null) !== 'NGN') {
            return false;
        }

        if (($verifiedData['reference'] ?? null) !== $payment->reference) {
            return false;
        }

        return true;
    }

    private function markPaymentAsPaid(SubscriptionPayment $payment, array $verifiedData): void
    {
        DB::transaction(function () use ($payment, $verifiedData) {
            $payment->update([
                'status' => PaymentStatus::Paid,
                'gateway_response' => json_encode(array_merge(
                    json_decode($payment->gateway_response ?? '{}', true),
                    ['paystack_verification' => $verifiedData]
                )),
                'paid_at' => now(),
            ]);

            $this->extendSubscription($payment);
        });
    }

    private function extendSubscription(SubscriptionPayment $payment): void
    {
        $plan = $payment->plan;
        $durationMonths = $this->getDurationFromMetadata($payment);

        $subscription = Subscription::firstOrNew(['pharmacy_id' => $payment->pharmacy_id]);

        $periodStart = ($subscription->exists
            && $subscription->current_period_ends_at !== null
            && $subscription->current_period_ends_at->isFuture())
            ? $subscription->current_period_ends_at
            : now();

        $periodEnd = $periodStart->copy()->addMonths($durationMonths);

        $subscription->fill([
            'subscription_plan_id' => $plan->id,
            'status' => SubscriptionStatus::Active,
            'current_period_starts_at' => $periodStart,
            'current_period_ends_at' => $periodEnd,
            'cancelled_at' => null,
            'renewal_reminder_sent_at' => null,
        ])->save();
    }

    private function getDurationFromMetadata(SubscriptionPayment $payment): int
    {
        $gatewayResponse = json_decode($payment->gateway_response ?? '{}', true);
        
        if (isset($gatewayResponse['duration_months'])) {
            return (int) $gatewayResponse['duration_months'];
        }
        
        $metadata = $gatewayResponse['metadata'] ?? [];
        if (isset($metadata['duration_months'])) {
            return (int) $metadata['duration_months'];
        }
        
        return 1;
    }
}