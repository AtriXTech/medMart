<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPriceChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Product $product)
    {
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('pharmacy.' . $this->product->pharmacy_id);
    }

    public function broadcastAs(): string
    {
        return 'price.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'price' => $this->product->price,
        ];
    }
}
