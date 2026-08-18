<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\SettlementAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SettlementAccountApproved extends Notification
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
            ->subject('Settlement Account Approved')
            ->line('Your settlement account has been approved.')
            ->line('Bank: ' . $this->account->bank_name)
            ->line('Account Name: ' . $this->account->account_name)
            ->line('Account Number: ' . $this->account->account_number)
            ->line('You can now receive payments to this account.')
            ->line('Thank you for using MedMart!');
    }
}