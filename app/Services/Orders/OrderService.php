<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\DeliveryStatus;
use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use App\Services\Inventory\StockService;
use App\Settlement\Enums\PaymentSettlementStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    private const array TRANSITIONS = [
        'pending_payment' => ['paid', 'cancelled'],
        'paid' => ['received', 'cancelled'],
        'received' => ['processing', 'cancelled'],
        'processing' => ['ready_for_pickup', 'cancelled'],
        'ready_for_pickup' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    private const array DELIVERY_RANK = [
        'pending' => 0,
        'dispatched' => 1,
        'delivered' => 2,
    ];

    public function __construct(private readonly StockService $stockService)
    {
    }

    public function transitionTo(Order $order, OrderStatus $target, ?User $performedBy = null, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $target, $performedBy, $reason) {
            $lockedOrder = Order::withoutGlobalScopes()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            $currentStatus = $lockedOrder->status->value;
            $allowed = self::TRANSITIONS[$currentStatus] ?? [];

            if (! in_array($target->value, $allowed, true)) {
                throw ValidationException::withMessages([
                    'status' => ["Cannot move an order from {$currentStatus} to {$target->value}."],
                ]);
            }

            $lockedOrder->update(['status' => $target]);

            if ($target === OrderStatus::Received) {
                $this->deductStockForOrder($lockedOrder, $performedBy);
                $this->clearCustomerCart($lockedOrder);
                $lockedOrder->update(['ready_at' => now()->addMinutes(30)]);
            }

            if ($target === OrderStatus::Completed) {
                $completedAt = now();

                $lockedOrder->update(['completed_at' => $completedAt]);

                if ($lockedOrder->fulfillment_type === FulfillmentType::Delivery) {
                    $lockedOrder->update(['delivery_status' => DeliveryStatus::Delivered]);
                }

                Payment::query()
                    ->withoutGlobalScopes()
                    ->where('order_id', $lockedOrder->id)
                    ->where('settlement_status', PaymentSettlementStatus::Pending->value)
                    ->whereNull('eligible_at')
                    ->update([
                        'eligible_at' => $completedAt->copy()->addHours((int) config('settlement.eligibility.hold_hours', 24)),
                    ]);
            }

            if ($target === OrderStatus::Cancelled) {
                $lockedOrder->update([
                    'cancelled_at' => now(),
                    'cancellation_reason' => $reason,
                ]);

                Payment::query()
                    ->withoutGlobalScopes()
                    ->where('order_id', $lockedOrder->id)
                    ->where('settlement_status', PaymentSettlementStatus::Pending->value)
                    ->update([
                        'settlement_status' => PaymentSettlementStatus::Held->value,
                        'hold_reason' => 'order_cancelled',
                        'eligible_at' => null,
                    ]);
            }

            $freshOrder = $lockedOrder->fresh(['items.product', 'customer']);

            if ($target !== OrderStatus::Paid) {
                DB::afterCommit(fn () => $freshOrder->customer->notify(new OrderStatusUpdated($freshOrder)));
            }

            return $freshOrder;
        });
    }

    public function updateDeliveryStatus(Order $order, DeliveryStatus $target, ?User $performedBy = null): Order
    {
        return DB::transaction(function () use ($order, $target, $performedBy) {
            $lockedOrder = Order::withoutGlobalScopes()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            if ($lockedOrder->fulfillment_type !== FulfillmentType::Delivery) {
                throw ValidationException::withMessages([
                    'delivery_status' => ['This order is not a delivery order.'],
                ]);
            }

            if (in_array($lockedOrder->status, [OrderStatus::Completed, OrderStatus::Cancelled], true)) {
                throw ValidationException::withMessages([
                    'delivery_status' => ["Delivery status can no longer be changed on a {$lockedOrder->status->value} order."],
                ]);
            }

            $current = $lockedOrder->delivery_status?->value ?? DeliveryStatus::Pending->value;

            if (self::DELIVERY_RANK[$target->value] < self::DELIVERY_RANK[$current]) {
                throw ValidationException::withMessages([
                    'delivery_status' => ["Cannot move delivery from {$current} to {$target->value}."],
                ]);
            }

            if ($target !== DeliveryStatus::Pending && $lockedOrder->status !== OrderStatus::ReadyForPickup) {
                throw ValidationException::withMessages([
                    'delivery_status' => ['The order must be ready for dispatch before delivery can progress.'],
                ]);
            }

            if ($target->value === $current) {
                return $lockedOrder->fresh(['items.product', 'customer']);
            }

            $lockedOrder->update(['delivery_status' => $target]);

            if ($target === DeliveryStatus::Delivered) {
                return $this->transitionTo($lockedOrder, OrderStatus::Completed, $performedBy);
            }

            return $lockedOrder->fresh(['items.product', 'customer']);
        });
    }

    private function deductStockForOrder(Order $order, ?User $performedBy): void
    {
        foreach ($order->items()->with('product')->get() as $item) {
            $this->stockService->deductStock(
                $item->product,
                $item->quantity,
                'Customer order',
                $performedBy,
                $order
            );
        }
    }

    private function clearCustomerCart(Order $order): void
    {
        Cart::where('customer_id', $order->customer_id)
            ->where('pharmacy_id', $order->pharmacy_id)
            ->first()
            ?->items()
            ->delete();
    }
}
