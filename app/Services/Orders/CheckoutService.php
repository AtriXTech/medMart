<?php

declare(strict_types=1);

namespace App\Services\Orders;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Pharmacy;
use App\Services\Prescriptions\PrescriptionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private readonly PrescriptionService $prescriptionService)
    {
    }

    public function checkout(
        Customer $customer,
        Pharmacy $pharmacy,
        Cart $cart,
        string $fulfillmentType,
        ?string $deliveryAddress
    ): Order {
        return DB::transaction(function () use ($customer, $pharmacy, $cart, $fulfillmentType, $deliveryAddress) {
            $cart->loadMissing('items.product');

            if ($cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Your cart is empty.'],
                ]);
            }

            $requiresPrescription = $cart->items->contains(fn ($item) => $item->product->requires_prescription);

            if ($requiresPrescription && ! $this->prescriptionService->customerHasApprovedPrescription($customer, $pharmacy)) {
                throw ValidationException::withMessages([
                    'prescription' => ['An approved prescription is required before checkout can be completed.'],
                ]);
            }

            $subtotal = 0;
            $lineItems = [];

            foreach ($cart->items as $item) {
                $product = $item->product;

                if (! $product->is_available) {
                    throw ValidationException::withMessages([
                        'cart' => ["{$product->name} is no longer available."],
                    ]);
                }

                if ($item->quantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'cart' => ["Only {$product->stock_quantity} unit(s) of {$product->name} available."],
                    ]);
                }

                $lineTotal = $product->price * $item->quantity;
                $subtotal += $lineTotal;

                $lineItems[] = [
                    'product' => $product,
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ];
            }

            $order = Order::create([
                'pharmacy_id' => $pharmacy->id,
                'customer_id' => $customer->id,
                'status' => OrderStatus::PendingPayment,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'fulfillment_type' => $fulfillmentType,
                'delivery_address' => $fulfillmentType === 'delivery' ? $deliveryAddress : null,
                'delivery_status' => $fulfillmentType === 'delivery' ? 'pending' : null,
            ]);

            foreach ($lineItems as $lineItem) {
                OrderItem::create([
                    'pharmacy_id' => $pharmacy->id,
                    'order_id' => $order->id,
                    'product_id' => $lineItem['product']->id,
                    'quantity' => $lineItem['quantity'],
                    'unit_price' => $lineItem['unit_price'],
                    'line_total' => $lineItem['line_total'],
                ]);
            }

            return $order->load('items.product');
        });
    }
}
