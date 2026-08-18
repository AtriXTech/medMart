<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Http\Resources\Customer\OrderResource;
use App\Services\Cart\CartService;
use App\Services\Orders\CheckoutService;
use Illuminate\Http\JsonResponse;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
    ) {
    }

    public function store(CheckoutRequest $request): JsonResponse
    {
        $pharmacy = $request->user()->activePharmacy();

        $cart = $this->cartService->getOrCreateCart($request->user(), $pharmacy);

        $order = $this->checkoutService->checkout(
            $request->user(),
            $pharmacy,
            $cart,
            $request->string('fulfillment_type')->toString(),
            $request->input('delivery_address')
        );

        return response()->json(new OrderResource($order), 201);
    }
}
