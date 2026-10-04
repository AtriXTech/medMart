<?php

declare(strict_types=1);

namespace App\Settlement\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class SettlementNoticeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $subjectLine,
        private readonly array $lines,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage())->subject($this->subjectLine);

        foreach ($this->lines as $line) {
            $message->line((string) $line);
        }

        return $message;
    }
}
