<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $pharmacy = $request->user()->activePharmacy();

        $products = Product::query()
            ->where('pharmacy_id', $pharmacy->id)
            ->where('is_available', true)
            ->with('category')
            ->when(
                $request->filled('category_id'),
                fn ($query) => $query->where('product_category_id', $request->integer('category_id'))
            )
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where('name', 'like', '%' . $request->string('search')->toString() . '%')
            )
            ->orderBy('name')
            ->paginate(20);

        return ProductResource::collection($products);
    }

    public function show(Request $request, Product $product): JsonResponse
    {
        $pharmacy = $request->user()->activePharmacy();

        abort_if($product->pharmacy_id !== $pharmacy->id || ! $product->is_available, 404);

        return response()->json(new ProductResource($product->load('category')));
    }
}
