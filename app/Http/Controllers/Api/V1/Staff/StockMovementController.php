<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Staff;

use App\Http\Controllers\Controller;
use App\Http\Resources\Staff\StockMovementResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StockMovementController extends Controller
{
    public function index(Product $product): AnonymousResourceCollection
    {
        return StockMovementResource::collection(
            $product->stockMovements()->with('staff')->latest()->paginate(20)
        );
    }
}
