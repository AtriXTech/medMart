<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\StaffRole;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionExpiringSoon;
use Illuminate\Console\Command;

class ManageSubscriptionExpirations extends Command
{
    protected $signature = 'subscriptions:manage-expirations';

    protected $description = 'Expires lapsed pharmacy subscriptions and notifies pharmacies approaching renewal.';

    public function handle(): int
    {
        $this->expireLapsedSubscriptions();
        $this->notifyUpcomingExpirations();

        return self::SUCCESS;
    }

    private function expireLapsedSubscriptions(): void
    {
        $expired = Subscription::where('status', SubscriptionStatus::Active)
            ->where('current_period_ends_at', '<', now())
            ->get();

        foreach ($expired as $subscription) {
            $subscription->update(['status' => SubscriptionStatus::Inactive]);
        }

        $this->info($expired->count() . ' subscription(s) expired.');
    }

    private function notifyUpcomingExpirations(): void
    {
        $warningWindow = now()->addDays(7);

        $upcoming = Subscription::where('status', SubscriptionStatus::Active)
            ->whereNull('renewal_reminder_sent_at')
            ->whereNotNull('current_period_ends_at')
            ->where('current_period_ends_at', '<=', $warningWindow)
            ->where('current_period_ends_at', '>=', now())
            ->get();

        foreach ($upcoming as $subscription) {
            $owners = User::where('pharmacy_id', $subscription->pharmacy_id)
                ->where('role', StaffRole::Owner)
                ->get();

            foreach ($owners as $owner) {
                $owner->notify(new SubscriptionExpiringSoon($subscription));
            }

            $subscription->update(['renewal_reminder_sent_at' => now()]);
        }

        $this->info($upcoming->count() . ' pharmacy subscription(s) reminded.');
    }
}
