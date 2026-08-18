<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\CreateCustomerAccountRequest;
use App\Http\Resources\Staff\CustomerLinkResource;
use App\Models\Customer;
use App\Models\CustomerPharmacyLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CustomerAccountController extends Controller
{
    public function store(CreateCustomerAccountRequest $request): JsonResponse
    {
        $customer = Customer::create([
            'username' => $request->string('username')->toString(),
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        $link = CustomerPharmacyLink::create([
            'customer_id' => $customer->id,
            'pharmacy_id' => $request->user()->pharmacy_id,
            'is_active' => true,
        ]);

        return response()->json(new CustomerLinkResource($link->load('customer')), 201);
    }
}