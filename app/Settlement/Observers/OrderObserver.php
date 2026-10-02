<?php

declare(strict_types=1);

namespace App\Settlement\Observers;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Settlement\Enums\PaymentSettlementStatus;
use App\Settlement\Services\Notifier;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

final class OrderObserver implements ShouldHandleEventsAfterCommit
{
    public function saved(Order $order): void
    {
        if ($order->status === OrderStatus::Completed) {
            $this->handleCompleted($order);

            return;
        }

        if ($order->status === OrderStatus::Cancelled) {
            $this->handleCancelled($order);
        }
    }

    private function handleCompleted(Order $order): void
    {
        $now = now();

        Order::query()
            ->withoutGlobalScopes()
            ->whereKey($order->getKey())
            ->whereNull('completed_at')
            ->update(['completed_at' => $now]);

        Payment::query()
            ->withoutGlobalScopes()
            ->where('order_id', $order->getKey())
            ->where('settlement_status', PaymentSettlementStatus::Pending->value)
            ->whereNull('eligible_at')
            ->update(['eligible_at' => $now->copy()->addHours((int) config('settlement.eligibility.hold_hours'))]);
    }

    private function handleCancelled(Order $order): void
    {
        $held = Payment::query()
            ->withoutGlobalScopes()
            ->where('order_id', $order->getKey())
            ->whereIn('settlement_status', [PaymentSettlementStatus::Pending->value, PaymentSettlementStatus::Eligible->value])
            ->update([
                'settlement_status' => PaymentSettlementStatus::Held->value,
                'hold_reason' => 'order_cancelled',
                'eligible_at' => null,
            ]);

        if ($held > 0) {
            app(Notifier::class)->ops('Paid order cancelled; refund required', [
                'Order ID: ' . $order->getKey(),
                'Payments held: ' . $held,
                'Refund the customer from the admin settlement tools.',
            ], 'warning');
        }
    }
}
