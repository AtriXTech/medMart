<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Services\Billing\PharmacySubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaystackWebhookController extends Controller
{
    public function __construct(
        private readonly PharmacySubscriptionService $subscriptionService
    ) {
    }

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        Log::info('Paystack webhook received', [
            'event' => $payload['event'] ?? 'unknown',
            'reference' => $payload['data']['reference'] ?? 'unknown',
        ]);

        try {
            $this->subscriptionService->handleWebhook($payload);
            return response()->json(['message' => 'Webhook processed successfully']);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }
}