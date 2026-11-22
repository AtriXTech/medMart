<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\Payments\Data\TransferResult;
use App\Payments\Enums\GatewayRole;
use App\Payments\Enums\TransferState;
use App\Payments\Exceptions\GatewayException;
use App\Payments\GatewayManager;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Services\TransferEventProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class GatewayController extends Controller
{
    public function __construct(
        private readonly GatewayManager $gateways,
        private readonly TransferEventProcessor $events,
    ) {
    }

    public function status(): JsonResponse
    {
        $balance = null;
        $error = null;
        $name = null;
        $label = null;
        $mode = 'live';

        try {
            $gateway = $this->gateways->payouts();
            $name = $gateway->gateway()->value;
            $label = $gateway->gateway()->label();
            $mode = $gateway->isTestMode() ? 'test' : 'live';
            $balance = $gateway->balanceKobo();
        } catch (GatewayException $exception) {
            $error = $exception->getMessage();
        }

        return response()->json([
            'data' => [
                'gateway' => $name,
                'gateway_label' => $label,
                'mode' => $mode,
                'balance_kobo' => $balance,
                'error' => $error,
            ],
        ]);
    }

    public function replay(Request $request, int $settlement): JsonResponse
    {
        $model = Settlement::query()->find($settlement)
            ?? throw SettlementException::notFound('Settlement not found.');

        $driver = $this->gateways->for($model->gateway ?? $this->gateways->settings()->active(GatewayRole::Payouts)->value);

        if (! $driver->isTestMode()) {
            throw SettlementException::forbidden('Event replay is only available in the ' . $driver->gateway()->label() . ' test environment.');
        }

        $data = $request->validate(['event' => ['required', 'in:success,failed,reversed']]);
        $event = (string) $data['event'];

        $state = match ($event) {
            'success' => TransferState::Success,
            'failed' => TransferState::Failed,
            default => TransferState::Reversed,
        };

        $this->events->apply($model, new TransferResult(
            $state,
            $model->gateway_reference,
            (int) $model->net_kobo,
            (string) config('settlement.currency'),
            'Replayed test event'
        ));

        return response()->json(['message' => 'Replayed ' . $event . ' for ' . $model->reference . '.']);
    }
}
