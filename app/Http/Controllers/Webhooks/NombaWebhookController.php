<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Payments\ChargeSettler;
use App\Payments\Enums\Gateway;
use App\Settlement\Jobs\ProcessTransferEventJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

final class NombaWebhookController extends Controller
{
    public function __construct(private readonly ChargeSettler $charges)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $event = (string) $request->input('event_type', 'unknown');
        $data = $request->input('data', []);
        $data = is_array($data) ? $data : [];

        try {
            match ($event) {
                'payment_success' => $this->handleCharge($data),
                'payout_success', 'payout_failed', 'payout_refund' => ProcessTransferEventJob::dispatch($event, $data, Gateway::Nomba->value),
                default => null,
            };
        } catch (Throwable $exception) {
            Log::error('Nomba webhook processing failed', [
                'event' => $event,
                'request_id' => (string) $request->input('requestId', ''),
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    private function handleCharge(array $data): void
    {
        $reference = trim((string) data_get($data, 'order.orderReference', ''));

        if ($reference === '') {
            return;
        }

        $this->charges->settle($reference);
    }
}
