<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('subscriptions:manage-expirations')->daily();

if ((bool) config('settlement.schedule.enabled', true)) {
    $settlementTimezone = (string) config('settlement.schedule.timezone', 'Africa/Lagos');

    Schedule::command('settlement:record-payments --sync')
        ->everyFiveMinutes()
        ->withoutOverlapping();

    Schedule::command('settlement:promote-eligible')
        ->everyFifteenMinutes()
        ->withoutOverlapping();

    Schedule::command('settlement:reconcile-transfers')
        ->everyTenMinutes()
        ->withoutOverlapping();

    Schedule::command('settlement:create-settlements')
        ->dailyAt((string) config('settlement.schedule.batch_at', '02:00'))
        ->timezone($settlementTimezone)
        ->withoutOverlapping();

    Schedule::command('settlement:reconcile-ledger')
        ->dailyAt((string) config('settlement.schedule.ledger_audit_at', '03:30'))
        ->timezone($settlementTimezone)
        ->withoutOverlapping();
}

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
