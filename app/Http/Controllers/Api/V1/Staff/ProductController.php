<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\StoreProductRequest;
use App\Http\Requests\Staff\UpdateProductAvailabilityRequest;
use App\Http\Requests\Staff\UpdateProductRequest;
use App\Http\Resources\Staff\ProductResource;
use App\Models\Product;
use App\Services\Realtime\BroadcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private readonly BroadcastService $broadcastService)
    {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::query()
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

    public function store(StoreProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->uploadImage($request->file('image'));
        }
        
        $product = Product::create($data);

        return response()->json(new ProductResource($product->load('category')), 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(new ProductResource($product->load('category')));
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('image')) {
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }
            $data['image_url'] = $this->uploadImage($request->file('image'));
        }
        
        $product->update($data);

        if ($product->wasChanged('price')) {
            $this->broadcastService->priceChanged($product);
        }

        if ($product->wasChanged('is_available')) {
            $this->broadcastService->availabilityChanged($product);
        }

        return response()->json(new ProductResource($product->load('category')));
    }

    public function updateAvailability(UpdateProductAvailabilityRequest $request, Product $product): JsonResponse
    {
        $product->update(['is_available' => $request->boolean('is_available')]);

        if ($product->wasChanged('is_available')) {
            $this->broadcastService->availabilityChanged($product);
        }

        return response()->json(new ProductResource($product->load('category')));
    }

    public function destroy(Product $product): JsonResponse
    {
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }
        
        $product->delete();

        return response()->json(['message' => 'Product deactivated.']);
    }

    private function uploadImage($image): string
    {
        $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('products', $filename, 'public');
        return $path;
    }
}