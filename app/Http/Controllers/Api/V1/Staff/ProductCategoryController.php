<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProductCategoryRequest;
use App\Http\Requests\Staff\UpdateProductCategoryRequest;
use App\Http\Resources\Staff\ProductCategoryResource;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductCategoryResource::collection(
            ProductCategory::withCount('products')
                ->orderBy('name')
                ->paginate(20)
        );
    }

    public function store(StoreProductCategoryRequest $request): JsonResponse
    {
        $category = ProductCategory::create($request->validated());

        return response()->json(new ProductCategoryResource($category), 201);
    }

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json(new ProductCategoryResource($category));
    }

    public function destroy(ProductCategory $category): JsonResponse
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted.']);
    }
}