<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use App\Services\Inventory\StockService;
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

    public function __construct(private readonly StockService $stockService)
    {
    }

    public function transitionTo(Order $order, OrderStatus $target, ?User $performedBy = null, ?string $reason = null): Order
    {
        return DB::transaction(function () use ($order, $target, $performedBy, $reason) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();

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

            if ($target === OrderStatus::Cancelled) {
                $lockedOrder->update([
                    'cancelled_at' => now(),
                    'cancellation_reason' => $reason,
                ]);
            }

            $freshOrder = $lockedOrder->fresh(['items.product', 'customer']);

            if ($target !== OrderStatus::Paid) {
                DB::afterCommit(fn () => $freshOrder->customer->notify(new OrderStatusUpdated($freshOrder)));
            }

            return $freshOrder;
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
