<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Http\Requests\ReasonRequest;
use App\Settlement\Http\Resources\PaymentRowResource;
use App\Settlement\Services\PaymentDisputeService;
use App\Settlement\Services\PaymentRefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

final class PaymentAdminController extends Controller
{
    public function __construct(
        private readonly PaymentRefundService $refunds,
        private readonly PaymentDisputeService $disputes,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $statuses = array_merge(
            array_map(static fn (PaymentSettlementStatus $status): string => $status->value, PaymentSettlementStatus::cases()),
            ['unrecorded']
        );

        $filters = $request->validate([
            'settlement_status' => ['sometimes', 'string', Rule::in($statuses)],
            'pharmacy_id' => ['sometimes', 'integer'],
            'order_id' => ['sometimes', 'integer'],
            'search' => ['sometimes', 'string', 'max:60'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ]);

        $payments = Payment::query()
            ->withoutGlobalScopes()
            ->with('pharmacy:id,name')
            ->when(
                ($filters['settlement_status'] ?? null) === 'unrecorded',
                static fn ($q) => $q->whereNull('settlement_status')->whereIn('status', (array) config('settlement.payment.paid_statuses'))
            )
            ->when(
                isset($filters['settlement_status']) && $filters['settlement_status'] !== 'unrecorded',
                static fn ($q) => $q->where('settlement_status', $filters['settlement_status'])
            )
            ->when(isset($filters['pharmacy_id']), static fn ($q) => $q->where('pharmacy_id', (int) $filters['pharmacy_id']))
            ->when(isset($filters['order_id']), static fn ($q) => $q->where('order_id', (int) $filters['order_id']))
            ->when(isset($filters['search']), static fn ($q) => $q->where('reference', 'like', '%' . $filters['search'] . '%'))
            ->orderByDesc('id')
            ->paginate((int) ($filters['per_page'] ?? 20));

        return PaymentRowResource::collection($payments);
    }

    public function refund(Request $request, int $payment): JsonResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:255'],
            'via_gateway' => ['sometimes', 'boolean'],
        ]);

        $refunded = $this->refunds->refund(
            $payment,
            (string) $data['reason'],
            (int) $request->user()->getKey(),
            (bool) ($data['via_gateway'] ?? true)
        );

        return response()->json(['message' => 'Payment refunded.', 'data' => $this->summary($refunded)]);
    }

    public function hold(ReasonRequest $request, int $payment): JsonResponse
    {
        $held = $this->disputes->hold($payment, (string) $request->validated('reason'), (int) $request->user()->getKey());

        return response()->json(['message' => 'Payment held.', 'data' => $this->summary($held)]);
    }

    public function release(Request $request, int $payment): JsonResponse
    {
        $released = $this->disputes->release($payment, (int) $request->user()->getKey());

        return response()->json(['message' => 'Payment released.', 'data' => $this->summary($released)]);
    }

    private function summary(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'order_id' => $payment->order_id,
            'settlement_status' => $payment->getAttribute('settlement_status'),
            'hold_reason' => $payment->getAttribute('hold_reason'),
            'net_kobo' => (int) $payment->getAttribute('net_kobo'),
        ];
    }
}
