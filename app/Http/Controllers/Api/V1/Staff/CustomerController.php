<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\CustomerLinkResource;
use App\Http\Resources\Staff\OrderResource;
use App\Models\CustomerPharmacyLink;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $links = CustomerPharmacyLink::query()
            ->with('customer')
            ->when(
                $request->filled('search'),
                fn ($query) => $query->whereHas(
                    'customer',
                    fn ($q) => $q->where('name', 'like', '%' . $request->string('search')->toString() . '%')
                        ->orWhere('username', 'like', '%' . $request->string('search')->toString() . '%')
                )
            )
            ->latest()
            ->paginate(20);

        return CustomerLinkResource::collection($links);
    }

    public function show(CustomerPharmacyLink $customerPharmacyLink): JsonResponse
    {
        return response()->json(new CustomerLinkResource($customerPharmacyLink->load('customer')));
    }

    public function orders(CustomerPharmacyLink $customerPharmacyLink): AnonymousResourceCollection
    {
        $orders = Order::where('customer_id', $customerPharmacyLink->customer_id)
            ->where('pharmacy_id', $customerPharmacyLink->pharmacy_id)
            ->with('items.product')
            ->latest()
            ->paginate(20);

        return OrderResource::collection($orders);
    }

    public function suspend(CustomerPharmacyLink $customerPharmacyLink): JsonResponse
    {
        $customerPharmacyLink->update(['is_suspended' => true]);

        return response()->json(new CustomerLinkResource($customerPharmacyLink->load('customer')));
    }

    public function unsuspend(CustomerPharmacyLink $customerPharmacyLink): JsonResponse
    {
        $customerPharmacyLink->update(['is_suspended' => false]);

        return response()->json(new CustomerLinkResource($customerPharmacyLink->load('customer')));
    }
}
