<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SettlementAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SettlementAccountRejected extends Notification
{
    use Queueable;

    public function __construct(private readonly SettlementAccount $account)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Settlement Account Rejected')
            ->line('Your settlement account has been rejected.')
            ->line('Bank: ' . $this->account->bank_name)
            ->line('Account Name: ' . $this->account->account_name)
            ->line('Account Number: ' . $this->account->account_number)
            ->line('Reason: ' . $this->account->rejection_reason)
            ->line('Please update your account details and resubmit.')
            ->line('Thank you for using MedMart!');
    }
}