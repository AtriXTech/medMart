<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    public function __construct(private readonly Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status->value,
            'message' => 'Your order #' . $this->order->id . ' is now ' . $this->order->status->value . '.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order_id' => $this->order->id,
            'status' => $this->order->status->value,
            'message' => 'Your order #' . $this->order->id . ' is now ' . $this->order->status->value . '.',
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('customer.' . $this->order->customer_id);
    }

    public function broadcastAs(): string
    {
        return 'order.status-updated';
    }
}
