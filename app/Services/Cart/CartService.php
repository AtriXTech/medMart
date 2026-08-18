<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function getOrCreateCart(Customer $customer, Pharmacy $pharmacy): Cart
    {
        return Cart::firstOrCreate([
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id,
        ]);
    }

    public function addItem(Customer $customer, Pharmacy $pharmacy, Product $product, int $quantity): Cart
    {
        return DB::transaction(function () use ($customer, $pharmacy, $product, $quantity) {
            if (! $product->is_available) {
                throw ValidationException::withMessages([
                    'product_id' => ['This product is not currently available.'],
                ]);
            }

            if ($quantity > $product->stock_quantity) {
                throw ValidationException::withMessages([
                    'quantity' => ['Only ' . $product->stock_quantity . ' unit(s) available.'],
                ]);
            }

            $cart = $this->getOrCreateCart($customer, $pharmacy);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($item) {
                $newQuantity = $item->quantity + $quantity;

                if ($newQuantity > $product->stock_quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => ['Only ' . $product->stock_quantity . ' unit(s) available.'],
                    ]);
                }

                $item->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }

            return $cart->fresh(['items.product']);
        });
    }

    public function updateItemQuantity(Customer $customer, CartItem $cartItem, int $quantity): Cart
    {
        $this->authorizeOwnership($customer, $cartItem);

        $product = $cartItem->product;

        if ($quantity > $product->stock_quantity) {
            throw ValidationException::withMessages([
                'quantity' => ['Only ' . $product->stock_quantity . ' unit(s) available.'],
            ]);
        }

        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->cart->fresh(['items.product']);
    }

    public function removeItem(Customer $customer, CartItem $cartItem): Cart
    {
        $this->authorizeOwnership($customer, $cartItem);

        $cart = $cartItem->cart;

        $cartItem->delete();

        return $cart->fresh(['items.product']);
    }

    private function authorizeOwnership(Customer $customer, CartItem $cartItem): void
    {
        if ($cartItem->cart->customer_id !== $customer->id) {
            abort(404);
        }
    }
}
