<?php

declare(strict_types=1);

namespace App\Services\Purchasing;

use App\Enums\PurchaseOrderStatus;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\User;
use App\Services\Inventory\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchasingService
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function createPurchaseOrder(Supplier $supplier, array $data, ?User $placedBy = null): PurchaseOrder
    {
        return DB::transaction(function () use ($supplier, $data, $placedBy) {
            $purchaseOrder = PurchaseOrder::create([
                'pharmacy_id' => $supplier->pharmacy_id,
                'supplier_id' => $supplier->id,
                'placed_by_id' => $placedBy?->id,
                'status' => PurchaseOrderStatus::Ordered,
                'expected_date' => $data['expected_date'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                PurchaseOrderItem::create([
                    'pharmacy_id' => $supplier->pharmacy_id,
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product->id,
                    'quantity_ordered' => $item['quantity_ordered'],
                    'cost_price' => $item['cost_price'],
                ]);
            }

            return $purchaseOrder->load('items.product', 'supplier', 'placedBy');
        });
    }

    public function receiveItems(PurchaseOrder $purchaseOrder, array $receivedItems, ?User $performedBy = null): PurchaseOrder
    {
        return DB::transaction(function () use ($purchaseOrder, $receivedItems, $performedBy) {
            foreach ($receivedItems as $received) {
                $item = PurchaseOrderItem::whereKey($received['purchase_order_item_id'])
                    ->where('purchase_order_id', $purchaseOrder->id)
                    ->lockForUpdate()
                    ->first();

                if (! $item) {
                    throw ValidationException::withMessages([
                        'items' => ["Item {$received['purchase_order_item_id']} does not belong to this purchase order."],
                    ]);
                }

                $remaining = $item->quantity_ordered - $item->quantity_received;

                if ($received['quantity_received'] > $remaining) {
                    throw ValidationException::withMessages([
                        'items' => ["Cannot receive more than the remaining {$remaining} unit(s) for item {$item->id}."],
                    ]);
                }

                $this->stockService->receiveBatch(
                    $item->product,
                    [
                        'supplier_id' => $purchaseOrder->supplier_id,
                        'batch_number' => $received['batch_number'],
                        'expiry_date' => $received['expiry_date'],
                        'quantity' => $received['quantity_received'],
                        'cost_price' => $received['cost_price'] ?? $item->cost_price,
                    ],
                    $performedBy,
                    $item
                );

                $item->update(['quantity_received' => $item->quantity_received + $received['quantity_received']]);
            }

            $this->syncPurchaseOrderStatus($purchaseOrder);

            return $purchaseOrder->fresh(['items.product', 'supplier', 'placedBy']);
        });
    }

    public function cancel(PurchaseOrder $purchaseOrder): PurchaseOrder
    {
        if ($purchaseOrder->status === PurchaseOrderStatus::Received) {
            throw ValidationException::withMessages([
                'status' => ['A fully received purchase order cannot be cancelled.'],
            ]);
        }

        $purchaseOrder->update(['status' => PurchaseOrderStatus::Cancelled]);

        return $purchaseOrder;
    }

    private function syncPurchaseOrderStatus(PurchaseOrder $purchaseOrder): void
    {
        $items = $purchaseOrder->items()->get();

        $fullyReceived = $items->every(fn (PurchaseOrderItem $item) => $item->quantity_received >= $item->quantity_ordered);
        $anyReceived = $items->contains(fn (PurchaseOrderItem $item) => $item->quantity_received > 0);

        $purchaseOrder->update([
            'status' => $fullyReceived
                ? PurchaseOrderStatus::Received
                : ($anyReceived ? PurchaseOrderStatus::PartiallyReceived : PurchaseOrderStatus::Ordered),
        ]);
    }
}