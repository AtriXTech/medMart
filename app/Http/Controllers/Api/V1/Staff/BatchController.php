<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Requests\Staff\AdjustBatchQuantityRequest;
use App\Http\Requests\Staff\StoreBatchRequest;
use App\Http\Resources\Staff\BatchResource;
use App\Models\Batch;
use App\Models\Product;
use App\Services\Inventory\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class BatchController extends Controller
{
    public function __construct(private readonly StockService $stockService) {}

    public function index(Product $product): AnonymousResourceCollection
    {
        return BatchResource::collection(
            $product->batches()->orderBy('expiry_date')->paginate(20)
        );
    }


    /**
     * Store a newly created batch for the specified product.
     * @param StoreBatchRequest $request The request containing the batch data.
     * @param Product $product The product for which the batch is being created.
     * @return JsonResponse A JSON response containing the newly created batch resource with a 201 status code.
     */


    public function store(StoreBatchRequest $request, Product $product): JsonResponse
    {
        $batch = $this->stockService->receiveBatch($product, $request->validated(), $request->user());

        return response()->json(new BatchResource($batch), 201);
    }

    public function update(AdjustBatchQuantityRequest $request, Product $product, Batch $batch): JsonResponse
    {
        abort_if($batch->product_id !== $product->id, 404);

        $batch = $this->stockService->adjustBatchQuantity(
            $batch,
            $request->integer('quantity'),
            $request->string('reason')->toString(),
            $request->user()
        );

        return response()->json(new BatchResource($batch));
    }

    public function expiringSoon(Request $request): JsonResponse
    {
        $pharmacyId = $request->user()->pharmacy_id;

        $batches = Batch::where('pharmacy_id', $pharmacyId)
            ->where('quantity', '>', 0)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->where('expiry_date', '>=', now())
            ->with('product')
            ->orderBy('expiry_date')
            ->paginate(20);

        return response()->json([
            'data' => $batches->items(),
            'meta' => [
                'current_page' => $batches->currentPage(),
                'last_page' => $batches->lastPage(),
                'total' => $batches->total(),
            ],
        ]);
    }
}
