<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PrescriptionReviewed extends Notification
{
    public function __construct(private readonly Prescription $prescription)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'prescription_id' => $this->prescription->id,
            'status' => $this->prescription->status->value,
            'message' => 'Your prescription was ' . $this->prescription->status->value . '.',
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'prescription_id' => $this->prescription->id,
            'status' => $this->prescription->status->value,
            'message' => 'Your prescription was ' . $this->prescription->status->value . '.',
        ]);
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('customer.' . $this->prescription->customer_id);
    }

    public function broadcastAs(): string
    {
        return 'prescription.reviewed';
    }
}
