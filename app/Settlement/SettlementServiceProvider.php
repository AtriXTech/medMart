<?php

declare(strict_types=1);

namespace App\Settlement;

use App\Models\Order;
use App\Models\Payment;
use App\Settlement\Console\CreateAdminTokenCommand;
use App\Settlement\Console\CreateSettlementsCommand;
use App\Settlement\Console\PromoteEligiblePaymentsCommand;
use App\Settlement\Console\ReconcileLedgerCommand;
use App\Settlement\Console\ReconcileTransfersCommand;
use App\Settlement\Console\RecordPaymentsCommand;
use App\Settlement\Console\SeedTestDataCommand;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Observers\OrderObserver;
use App\Settlement\Observers\PaymentObserver;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

final class SettlementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/settlement.php', 'settlement');

        $this->app->singleton(PaystackClient::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'settlement');
        $this->loadRoutesFrom(__DIR__ . '/Routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        Payment::observe(PaymentObserver::class);
        Order::observe(OrderObserver::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                RecordPaymentsCommand::class,
                PromoteEligiblePaymentsCommand::class,
                CreateSettlementsCommand::class,
                ReconcileTransfersCommand::class,
                ReconcileLedgerCommand::class,
                CreateAdminTokenCommand::class,
                SeedTestDataCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/Config/settlement.php' => config_path('settlement.php'),
            ], 'settlement-config');
        }

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
            $this->registerSchedule($schedule);
        });
    }

    private function registerSchedule(Schedule $schedule): void
    {
        if (! (bool) config('settlement.schedule.enabled')) {
            return;
        }

        $timezone = (string) config('settlement.schedule.timezone');

        $schedule->command('settlement:record-payments')
            ->everyFiveMinutes()
            ->withoutOverlapping(10)
            ->onOneServer();

        $schedule->command('settlement:promote-eligible')
            ->hourly()
            ->withoutOverlapping(30)
            ->onOneServer();

        $schedule->command('settlement:create-settlements')
            ->dailyAt((string) config('settlement.schedule.batch_at'))
            ->timezone($timezone)
            ->withoutOverlapping(120)
            ->onOneServer();

        $schedule->command('settlement:reconcile-transfers')
            ->everyFifteenMinutes()
            ->withoutOverlapping(30)
            ->onOneServer();

        $schedule->command('settlement:reconcile-ledger')
            ->dailyAt((string) config('settlement.schedule.ledger_audit_at'))
            ->timezone($timezone)
            ->withoutOverlapping(120)
            ->onOneServer();
    }
}
