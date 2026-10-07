<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Billing\PharmacySubscriptionService;
use App\Services\Payments\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function __construct(
        private readonly PharmacySubscriptionService $subscriptionService,
        private readonly PaymentService $paymentService,
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = (string) ($payload['event'] ?? 'unknown');
        $data = is_array($payload['data'] ?? null) ? $payload['data'] : [];
        $reference = (string) ($data['reference'] ?? '');

        Log::info('Paystack webhook received', [
            'event' => $event,
            'reference' => $reference !== '' ? $reference : 'unknown',
        ]);

        try {
            if ($this->isOrderPayment($data, $reference)) {
                if ($event === 'charge.success' && $reference !== '') {
                    $this->paymentService->handleSuccessfulCharge($reference);
                }
            } else {
                $this->subscriptionService->handleWebhook($payload);
            }

            return response()->json(['message' => 'Webhook processed successfully']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    private function isOrderPayment(array $data, string $reference): bool
    {
        $metadata = $data['metadata'] ?? null;
        $type = is_array($metadata) ? ($metadata['payment_type'] ?? null) : null;

        if ($type === 'order') {
            return true;
        }

        if ($type === 'subscription') {
            return false;
        }

        if ($reference === '') {
            return false;
        }

        return Payment::query()->withoutGlobalScopes()->where('reference', $reference)->exists();
    }
}
