<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Pharmacy;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Exceptions\SettlementException;
use Illuminate\Support\Facades\DB;

final class PaymentDisputeService
{
    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function hold(int $paymentId, string $reason, ?int $actorId): Payment
    {
        return $this->transition($paymentId, function (Payment $payment) use ($reason, $actorId): void {
            $status = $payment->getAttribute('settlement_status');

            if (! in_array($status, [PaymentSettlementStatus::Pending->value, PaymentSettlementStatus::Eligible->value], true)) {
                throw SettlementException::invalidState('Only pending or eligible payments can be held.');
            }

            $payment->forceFill([
                'settlement_status' => PaymentSettlementStatus::Held->value,
                'hold_reason' => mb_substr($reason, 0, 255),
                'eligible_at' => null,
            ])->saveQuietly();

            $this->audit->record('payment.held', $payment->pharmacy_id, $payment, $actorId, ['reason' => $reason]);
        });
    }

    public function release(int $paymentId, ?int $actorId): Payment
    {
        return $this->transition($paymentId, function (Payment $payment) use ($actorId): void {
            if ($payment->getAttribute('settlement_status') !== PaymentSettlementStatus::Held->value) {
                throw SettlementException::invalidState('Only held payments can be released.');
            }

            $order = Order::query()->withoutGlobalScopes()->find($payment->order_id);

            if ($order?->status === OrderStatus::Cancelled) {
                throw SettlementException::invalidState('The order was cancelled. Refund this payment instead.');
            }

            $completed = $order?->status === OrderStatus::Completed;

            $payment->forceFill([
                'settlement_status' => PaymentSettlementStatus::Pending->value,
                'hold_reason' => null,
                'eligible_at' => $completed ? now() : null,
            ])->saveQuietly();

            $this->audit->record('payment.released', $payment->pharmacy_id, $payment, $actorId);
        });
    }

    private function transition(int $paymentId, callable $apply): Payment
    {
        $probe = Payment::query()->withoutGlobalScopes()->find($paymentId)
            ?? throw SettlementException::notFound('Payment not found.');

        return DB::transaction(function () use ($probe, $apply): Payment {
            Pharmacy::query()->withoutGlobalScopes()->whereKey($probe->pharmacy_id)->lockForUpdate()->first();

            $payment = Payment::query()->withoutGlobalScopes()->whereKey($probe->id)->lockForUpdate()->first()
                ?? throw SettlementException::notFound('Payment not found.');

            $apply($payment);

            return $payment;
        });
    }
}
