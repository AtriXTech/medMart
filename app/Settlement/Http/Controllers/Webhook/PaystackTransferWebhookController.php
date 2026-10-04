<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Settlement\Jobs\ProcessTransferEventJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PaystackTransferWebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $event = (string) $request->input('event', '');
        $data = $request->input('data', []);

        if (str_starts_with($event, 'transfer.') && is_array($data)) {
            ProcessTransferEventJob::dispatch($event, $data);
        }

        return response()->json(['received' => true]);
    }
}
