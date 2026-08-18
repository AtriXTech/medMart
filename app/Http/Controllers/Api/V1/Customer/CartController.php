<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Http\Requests\Customer\UpdateCartItemRequest;
use App\Http\Resources\Customer\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Cart\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function show(Request $request): JsonResponse
    {
        $pharmacy = $request->user()->activePharmacy();

        $cart = $this->cartService->getOrCreateCart($request->user(), $pharmacy);

        return response()->json(new CartResource($cart->load('items.product')));
    }

    public function addItem(AddToCartRequest $request): JsonResponse
    {
        $pharmacy = $request->user()->activePharmacy();

        $product = Product::where('pharmacy_id', $pharmacy->id)->findOrFail($request->integer('product_id'));

        $cart = $this->cartService->addItem($request->user(), $pharmacy, $product, $request->integer('quantity'));

        return response()->json(new CartResource($cart), 201);
    }

    public function updateItem(UpdateCartItemRequest $request, CartItem $cartItem): JsonResponse
    {
        $cart = $this->cartService->updateItemQuantity($request->user(), $cartItem, $request->integer('quantity'));

        return response()->json(new CartResource($cart));
    }

    public function removeItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $cart = $this->cartService->removeItem($request->user(), $cartItem);

        return response()->json(new CartResource($cart));
    }
}
