<?php

declare(strict_types=1);

namespace App\Services\Realtime;

use App\Events\ProductAvailabilityChanged;
use App\Events\ProductPriceChanged;
use App\Events\StockLevelChanged;
use App\Models\Product;

class BroadcastService
{
    public function stockChanged(Product $product): void
    {
        broadcast(new StockLevelChanged($product));
    }

    public function priceChanged(Product $product): void
    {
        broadcast(new ProductPriceChanged($product));
    }

    public function availabilityChanged(Product $product): void
    {
        broadcast(new ProductAvailabilityChanged($product));
    }
}
