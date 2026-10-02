<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pharmacy;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Services\BalanceService;
use App\Settlement\Services\SettlementAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class PharmacyPayoutController extends Controller
{
    public function __construct(
        private readonly SettlementAdminService $admin,
        private readonly BalanceService $balances,
    ) {
    }

    public function show(int $pharmacy): JsonResponse
    {
        $model = Pharmacy::query()->withoutGlobalScopes()->find($pharmacy)
            ?? throw SettlementException::notFound('Pharmacy not found.');

        return response()->json([
            'data' => [
                'pharmacy' => $this->payload($model),
                'balance' => $this->balances->forPharmacy((int) $model->id),
            ],
        ]);
    }

    public function update(Request $request, int $pharmacy): JsonResponse
    {
        $values = $request->validate([
            'payout_hold' => ['sometimes', 'boolean'],
            'payout_hold_reason' => ['sometimes', 'nullable', 'string', 'max:255'],
            'commission_rate' => ['sometimes', 'nullable', 'numeric', 'between:0,100'],
            'payout_schedule' => ['sometimes', Rule::in(['daily', 'weekly', 'manual'])],
        ]);

        $updated = $this->admin->updatePharmacyPayout($pharmacy, $values, (int) $request->user()->getKey());

        return response()->json([
            'message' => 'Pharmacy payout settings updated.',
            'data' => ['pharmacy' => $this->payload($updated)],
        ]);
    }

    private function payload(Pharmacy $pharmacy): array
    {
        return [
            'id' => $pharmacy->id,
            'name' => $pharmacy->name,
            'is_test_account' => (bool) $pharmacy->getAttribute('is_test_account'),
            'payout_hold' => (bool) $pharmacy->getAttribute('payout_hold'),
            'payout_hold_reason' => $pharmacy->getAttribute('payout_hold_reason'),
            'payout_hold_until' => $pharmacy->getAttribute('payout_hold_until'),
            'commission_rate' => $pharmacy->getAttribute('commission_rate'),
            'payout_schedule' => (string) ($pharmacy->getAttribute('payout_schedule') ?? 'daily'),
            'consecutive_payout_failures' => (int) $pharmacy->getAttribute('consecutive_payout_failures'),
        ];
    }
}
