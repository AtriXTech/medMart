<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Settlement\Http\Requests\ReasonRequest;
use App\Settlement\Services\AuditLogger;
use App\Settlement\Services\SettingsService;
use App\Settlement\Services\SettlementAdminService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class ControlController extends Controller
{
    public function __construct(
        private readonly SettingsService $settings,
        private readonly SettlementAdminService $admin,
        private readonly AuditLogger $audit,
    ) {
    }

    public function show(): JsonResponse
    {
        return response()->json(['data' => $this->settings->all()]);
    }

    public function update(Request $request): JsonResponse
    {
        $values = $request->validate([
            'commission_enabled' => ['sometimes', 'boolean'],
            'commission_rate' => ['sometimes', 'numeric', 'between:0,100'],
            'commission_flat_kobo' => ['sometimes', 'integer', 'min:0'],
            'commission_cap_kobo' => ['sometimes', 'integer', 'min:0'],
            'gateway_fee_bearer' => ['sometimes', Rule::in(['pharmacy', 'platform'])],
        ]);

        $actorId = (int) $request->user()->getKey();

        $this->settings->update($values, $actorId);
        $this->audit->record('settlement.settings_updated', null, null, $actorId, $values);

        return response()->json(['message' => 'Settings updated.', 'data' => $this->settings->all()]);
    }

    public function pause(ReasonRequest $request): JsonResponse
    {
        $this->admin->pausePayouts((string) $request->validated('reason'), (int) $request->user()->getKey());

        return response()->json(['message' => 'All payouts paused.', 'data' => $this->settings->all()]);
    }

    public function resume(Request $request): JsonResponse
    {
        $released = $this->admin->resumePayouts((int) $request->user()->getKey());

        return response()->json([
            'message' => 'Payouts resumed.',
            'released_settlements' => $released,
            'data' => $this->settings->all(),
        ]);
    }
}
