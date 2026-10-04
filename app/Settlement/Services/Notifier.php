<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use App\Settlement\Notifications\SettlementNoticeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Throwable;

final class Notifier
{
    public function pharmacy(Pharmacy|int|string|null $pharmacy, string $subject, array $lines): void
    {
        if (! (bool) config('settlement.notifications.pharmacy')) {
            return;
        }

        if (is_int($pharmacy) || is_string($pharmacy)) {
            $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find((int) $pharmacy);
        }

        $email = $pharmacy?->email;

        if (! is_string($email) || $email === '') {
            return;
        }

        try {
            Notification::route('mail', $email)->notify(new SettlementNoticeNotification($subject, $lines));
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    public function ops(string $subject, array $lines, string $level = 'warning'): void
    {
        $level = in_array($level, ['notice', 'warning', 'error', 'critical'], true) ? $level : 'warning';

        Log::log($level, '[settlement] ' . $subject, ['lines' => $lines]);

        $email = config('settlement.notifications.ops_email');

        if (! is_string($email) || $email === '') {
            return;
        }

        try {
            Notification::route('mail', $email)->notify(new SettlementNoticeNotification('[Settlement] ' . $subject, $lines));
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
