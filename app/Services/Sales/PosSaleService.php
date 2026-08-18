<?php

declare(strict_types=1);

namespace App\Services\Sales;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\User;
use App\Services\Inventory\StockService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosSaleService
{
    public function __construct(private readonly StockService $stockService)
    {
    }

    public function createSale(array $data, User $cashier): Sale
    {
        return DB::transaction(function () use ($data, $cashier) {
            $subtotal = 0;
            $discountTotal = 0;
            $lineItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $discount = $item['discount'] ?? 0;
                $unitPrice = (float) $product->price;
                $lineSubtotal = $unitPrice * $quantity;

                if ($discount > $lineSubtotal) {
                    throw ValidationException::withMessages([
                        'items' => ["Discount cannot exceed the line total for {$product->name}."],
                    ]);
                }

                $lineTotal = $lineSubtotal - $discount;

                $subtotal += $lineSubtotal;
                $discountTotal += $discount;

                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'line_total' => $lineTotal,
                ];
            }

            $sale = Sale::create([
                'pharmacy_id' => $cashier->pharmacy_id,
                'cashier_id' => $cashier->id,
                'customer_name' => $data['customer_name'] ?? null,
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'total' => $subtotal - $discountTotal,
            ]);

            foreach ($lineItems as $lineItem) {
                SaleItem::create([
                    'pharmacy_id' => $cashier->pharmacy_id,
                    'sale_id' => $sale->id,
                    'product_id' => $lineItem['product']->id,
                    'quantity' => $lineItem['quantity'],
                    'unit_price' => $lineItem['unit_price'],
                    'discount' => $lineItem['discount'],
                    'line_total' => $lineItem['line_total'],
                ]);

                $this->stockService->deductStock(
                    $lineItem['product'],
                    $lineItem['quantity'],
                    'POS sale',
                    $cashier,
                    $sale
                );
            }

            return $sale->load('items.product', 'cashier');
        });
    }
}
