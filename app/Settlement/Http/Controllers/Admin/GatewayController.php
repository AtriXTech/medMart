<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Services\TransferEventProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GatewayController extends Controller
{
    public function __construct(
        private readonly PaystackClient $paystack,
        private readonly TransferEventProcessor $events,
    ) {
    }

    public function status(): JsonResponse
    {
        $balance = null;
        $error = null;

        try {
            $balance = $this->paystack->balance();
        } catch (PaystackException $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'data' => [
                'mode' => $this->paystack->isTestKey() ? 'test' : 'live',
                'balance_kobo' => $balance,
                'error' => $error,
            ],
        ]);
    }

    public function replay(Request $request, int $settlement): JsonResponse
    {
        if (! $this->paystack->isTestKey()) {
            throw SettlementException::forbidden('Event replay is only available with a Paystack test key.');
        }

        $data = $request->validate(['event' => ['required', 'in:success,failed,reversed']]);
        $event = (string) $data['event'];

        $model = Settlement::query()->find($settlement)
            ?? throw SettlementException::notFound('Settlement not found.');

        $this->events->handleWebhook('transfer.' . $event, [
            'reference' => $model->reference,
            'transfer_code' => $model->gateway_reference,
            'amount' => (int) $model->net_kobo,
            'currency' => (string) config('settlement.paystack.currency'),
            'status' => $event,
            'complete_message' => 'Replayed test event',
        ]);

        return response()->json(['message' => 'Replayed transfer.' . $event . ' for ' . $model->reference . '.']);
    }
}
