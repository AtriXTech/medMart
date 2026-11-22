<?php

declare(strict_types=1);

namespace App\Services\Billing;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Pharmacy;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use App\Payments\Data\VerifiedCharge;
use App\Payments\Enums\ChargeState;
use App\Payments\GatewayManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PharmacySubscriptionService
{
    public function __construct(
        private readonly GatewayManager $gateways
    ) {
    }

    public function initiate(Pharmacy $pharmacy, SubscriptionPlan $plan, int $durationMonths = 1): array
    {
        $gateway = $this->gateways->collections();
        $totalAmount = $plan->price * $durationMonths;
        $reference = 'MEDMART-SUB-' . $pharmacy->id . '-' . Str::random(10);

        SubscriptionPayment::create([
            'pharmacy_id' => $pharmacy->id,
            'subscription_plan_id' => $plan->id,
            'reference' => $reference,
            'gateway' => $gateway->gateway()->value,
            'status' => PaymentStatus::Unpaid,
            'amount' => $totalAmount,
            'gateway_response' => json_encode([
                'duration_months' => $durationMonths,
                'subscription_plan_id' => $plan->id,
                'pharmacy_id' => $pharmacy->id,
            ]),
        ]);

        $session = $gateway->initializeCharge(
            $reference,
            (int) round($totalAmount * 100),
            $pharmacy->email,
            [
                'pharmacy_id' => $pharmacy->id,
                'subscription_plan_id' => $plan->id,
                'duration_months' => $durationMonths,
                'payment_type' => 'subscription',
            ],
            $gateway->staffCallbackUrl()
        );

        return [
            'authorization_url' => $session->authorizationUrl,
            'reference' => $reference,
            'amount' => $totalAmount,
        ];
    }

    public function verifyPayment(Pharmacy $pharmacy, string $reference): array
    {
        $payment = SubscriptionPayment::where('pharmacy_id', $pharmacy->id)
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
                'subscription' => $pharmacy->subscription?->load('plan'),
            ];
        }

        try {
            $charge = $this->gateways->for($payment->gateway)->verifyCharge($payment->reference);

            if ($charge->state === ChargeState::Pending) {
                return [
                    'status' => 'pending',
                    'message' => 'Your payment is still being processed. Please check again shortly.',
                ];
            }

            if (! $this->isValidVerification($charge, $payment)) {
                $payment->update([
                    'status' => PaymentStatus::Failed,
                    'gateway_response' => json_encode(array_merge(
                        json_decode($payment->gateway_response ?? '{}', true) ?: [],
                        $charge->raw
                    )),
                ]);

                Log::warning('Payment verification failed', [
                    'reference' => $payment->reference,
                    'payment_id' => $payment->id,
                    'gateway' => $payment->gateway,
                    'gateway_status' => $charge->rawStatus,
                ]);

                return [
                    'status' => 'failed',
                    'message' => 'Payment verification failed. Please contact support.',
                ];
            }

            $this->markPaymentAsPaid($payment, $charge);

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

    public function settleReference(string $reference): void
    {
        $payment = SubscriptionPayment::withoutGlobalScopes()->where('reference', $reference)->first();

        if (! $payment || $payment->status === PaymentStatus::Paid) {
            return;
        }

        $pharmacy = Pharmacy::withoutGlobalScopes()->find($payment->pharmacy_id);

        if ($pharmacy === null) {
            return;
        }

        $this->verifyPayment($pharmacy, $reference);
    }

    public function handleWebhook(array $payload): void
    {
        if (($payload['event'] ?? null) !== 'charge.success') {
            return;
        }

        $reference = $payload['data']['reference'] ?? null;

        if (! $reference) {
            return;
        }

        try {
            $this->settleReference((string) $reference);
        } catch (\Exception $e) {
            Log::error('Failed to process webhook payment', [
                'reference' => $reference,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function isValidVerification(VerifiedCharge $charge, SubscriptionPayment $payment): bool
    {
        return $charge->isSuccessful()
            && $charge->amountKobo === (int) round($payment->amount * 100)
            && $charge->currency === strtoupper((string) config('settlement.currency', 'NGN'))
            && $charge->reference === $payment->reference;
    }

    private function markPaymentAsPaid(SubscriptionPayment $payment, VerifiedCharge $charge): void
    {
        DB::transaction(function () use ($payment, $charge) {
            $payment->update([
                'status' => PaymentStatus::Paid,
                'gateway_reference' => $charge->gatewayReference ?? $payment->reference,
                'gateway_response' => json_encode(array_merge(
                    json_decode($payment->gateway_response ?? '{}', true) ?: [],
                    ['gateway_verification' => $charge->raw]
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
        $gatewayResponse = json_decode($payment->gateway_response ?? '{}', true) ?: [];

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
