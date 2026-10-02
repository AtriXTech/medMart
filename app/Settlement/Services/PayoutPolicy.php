<?php

declare(strict_types=1);

namespace App\Settlement\Services;

use App\Models\Pharmacy;
use Illuminate\Support\Carbon;

final class PayoutPolicy
{
    public function canReceivePayouts(Pharmacy $pharmacy): bool
    {
        if ((bool) $pharmacy->getAttribute('is_test_account')) {
            return false;
        }

        if ((bool) $pharmacy->getAttribute('payout_hold')) {
            return false;
        }

        $until = $pharmacy->getAttribute('payout_hold_until');

        return $until === null || ! Carbon::parse($until)->isFuture();
    }

    public function scheduleAllowsToday(Pharmacy $pharmacy): bool
    {
        $schedule = (string) ($pharmacy->getAttribute('payout_schedule') ?? 'daily');
        $today = now((string) config('settlement.schedule.timezone'))->dayOfWeekIso;

        return match ($schedule) {
            'manual' => false,
            'weekly' => $today === (int) config('settlement.payout.weekly_day'),
            default => true,
        };
    }
}
