<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\FulfillmentType;
use App\Enums\OrderStatus;
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
        return $this->payload();
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->payload());
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('customer.' . $this->order->customer_id);
    }

    public function broadcastAs(): string
    {
        return 'order.status-updated';
    }

    private function payload(): array
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status->value,
            'message' => 'Your order #' . $this->order->id . ' ' . $this->phrase() . '.',
        ];
    }

    private function phrase(): string
    {
        $isDelivery = $this->order->fulfillment_type === FulfillmentType::Delivery;

        return match ($this->order->status) {
            OrderStatus::PendingPayment => 'is awaiting payment',
            OrderStatus::Paid => 'has been paid for',
            OrderStatus::Received => 'has been received by the pharmacy',
            OrderStatus::Processing => 'is being processed',
            OrderStatus::ReadyForPickup => $isDelivery ? 'is ready for dispatch' : 'is ready for pickup',
            OrderStatus::Completed => $isDelivery ? 'has been delivered' : 'has been picked up',
            OrderStatus::Cancelled => 'has been cancelled',
        };
    }
}
