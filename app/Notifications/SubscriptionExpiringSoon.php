<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringSoon extends Notification
{
    public function __construct(private readonly Subscription $subscription)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'subscription_id' => $this->subscription->id,
            'expires_at' => $this->subscription->current_period_ends_at?->toDateString(),
            'message' => 'Your MedMart subscription expires on '
                . $this->subscription->current_period_ends_at?->toDateString()
                . '. Please renew to avoid interruption.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your MedMart subscription is expiring soon')
            ->line('Your subscription for ' . $notifiable->pharmacy->name . ' expires on '
                . $this->subscription->current_period_ends_at?->toDateString() . '.')
            ->line('Please renew soon to avoid any interruption to your pharmacy dashboard access.');
    }
}
