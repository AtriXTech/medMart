<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreSupplierRequest;
use App\Http\Requests\Staff\UpdateSupplierRequest;
use App\Http\Resources\Staff\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SupplierController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SupplierResource::collection(
            Supplier::orderBy('name')->paginate(20)
        );
    }

    public function store(StoreSupplierRequest $request): JsonResponse
    {
        $supplier = Supplier::create($request->validated());

        return response()->json(new SupplierResource($supplier), 201);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        $supplier->update($request->validated());

        return response()->json(new SupplierResource($supplier));
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        if ($supplier->purchaseOrders()->exists()) {
            return response()->json([
                'message' => 'This supplier has purchase orders and cannot be deleted.',
            ], 422);
        }

        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted.']);
    }
}
