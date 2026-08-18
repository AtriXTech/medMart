<?php

declare(strict_types=1);

namespace App\Services\Inventory;

use App\Enums\StockMovementType;
use App\Exceptions\InsufficientStockException;
use App\Models\Batch;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\Realtime\BroadcastService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(private readonly BroadcastService $broadcastService)
    {
    }

    public function receiveBatch(Product $product, array $data, ?User $performedBy = null, ?PurchaseOrderItem $purchaseOrderItem = null): Batch
    {
        return DB::transaction(function () use ($product, $data, $performedBy, $purchaseOrderItem) {
            $batch = Batch::create([
                'pharmacy_id' => $product->pharmacy_id,
                'product_id' => $product->id,
                'supplier_id' => $data['supplier_id'] ?? null,
                'purchase_order_item_id' => $purchaseOrderItem?->id,
                'batch_number' => $data['batch_number'],
                'expiry_date' => $data['expiry_date'],
                'quantity' => $data['quantity'],
                'cost_price' => $data['cost_price'],
            ]);

            $this->recordMovement($product, $batch, $data['quantity'], StockMovementType::In, 'Batch received', $performedBy, $purchaseOrderItem);
            $this->syncProductStockQuantity($product);
            $this->broadcastStockChangeAfterCommit($product);

            return $batch;
        });
    }

    public function adjustBatchQuantity(Batch $batch, int $newQuantity, string $reason, ?User $performedBy = null): Batch
    {
        return DB::transaction(function () use ($batch, $newQuantity, $reason, $performedBy) {
            $lockedBatch = Batch::whereKey($batch->id)->lockForUpdate()->firstOrFail();

            $delta = $newQuantity - $lockedBatch->quantity;

            if ($delta === 0) {
                return $lockedBatch;
            }

            $lockedBatch->update(['quantity' => $newQuantity]);

            $product = $lockedBatch->product()->lockForUpdate()->firstOrFail();

            $this->recordMovement(
                $product,
                $lockedBatch,
                $delta,
                $delta > 0 ? StockMovementType::In : StockMovementType::Out,
                $reason,
                $performedBy
            );

            $this->syncProductStockQuantity($product);
            $this->broadcastStockChangeAfterCommit($product);

            return $lockedBatch;
        });
    }

    public function deductStock(
        Product $product,
        int $quantity,
        string $reason,
        ?User $performedBy = null,
        ?Model $reference = null
    ): void {
        DB::transaction(function () use ($product, $quantity, $reason, $performedBy, $reference) {
            $lockedProduct = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();

            $batches = Batch::where('product_id', $lockedProduct->id)
                ->where('quantity', '>', 0)
                ->orderBy('expiry_date')
                ->lockForUpdate()
                ->get();

            $remaining = $quantity;

            foreach ($batches as $batch) {
                if ($remaining <= 0) {
                    break;
                }

                $take = min($batch->quantity, $remaining);

                $batch->update(['quantity' => $batch->quantity - $take]);

                $this->recordMovement($lockedProduct, $batch, -$take, StockMovementType::Out, $reason, $performedBy, $reference);

                $remaining -= $take;
            }

            if ($remaining > 0) {
                throw new InsufficientStockException(
                    "Not enough stock for {$lockedProduct->name}. Short by {$remaining} unit(s)."
                );
            }

            $this->syncProductStockQuantity($lockedProduct);
            $this->broadcastStockChangeAfterCommit($lockedProduct);
        });
    }

    public function syncProductStockQuantity(Product $product): void
    {
        $total = Batch::where('product_id', $product->id)->sum('quantity');
        $product->update(['stock_quantity' => $total]);
    }

    private function broadcastStockChangeAfterCommit(Product $product): void
    {
        DB::afterCommit(fn () => $this->broadcastService->stockChanged($product->fresh()));
    }

    private function recordMovement(
        Product $product,
        ?Batch $batch,
        int $quantity,
        StockMovementType $type,
        string $reason,
        ?User $performedBy,
        ?Model $reference = null
    ): void {
        StockMovement::create([
            'pharmacy_id' => $product->pharmacy_id,
            'product_id' => $product->id,
            'batch_id' => $batch?->id,
            'staff_id' => $performedBy?->id,
            'type' => $type,
            'quantity' => $quantity,
            'reason' => $reason,
            'reference_type' => $reference ? $reference::class : null,
            'reference_id' => $reference?->id,
        ]);
    }
}