<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Pharmacy;
use App\Models\SettlementAccount;
use App\Models\SubscriptionPayment;
use Filament\Widgets\Widget;

class PlatformActivity extends Widget
{
    protected string $view = 'filament.widgets.platform-activity';

    protected static ?int $sort = 4; // Appears first

    protected int | string | array $columnSpan = 'full'; // Takes 100% width

    protected function getViewData(): array
    {
        $activities = collect();

        // New pharmacy registrations
        $pharmacies = Pharmacy::query()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($pharmacy) {
                return [
                    'type' => 'pharmacy',
                    'title' => 'New pharmacy registered',
                    'name' => $pharmacy->name,
                    'time' => $pharmacy->created_at,
                ];
            });

        // New customer registrations
        $customers = Customer::query()
            ->latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'type' => 'customer',
                    'title' => 'New customer joined',
                    'name' => $customer->name,
                    'time' => $customer->created_at,
                ];
            });

        // Activated subscriptions
        $subscriptions = SubscriptionPayment::query()
            ->where('status', 'paid')
            ->whereNotNull('paid_at')
            ->with(['pharmacy', 'subscriptionPlan'])
            ->latest('paid_at')
            ->take(10)
            ->get()
            ->map(function ($payment) {
                return [
                    'type' => 'subscription',
                    'title' => 'Subscription activated',
                    'name' => $payment->pharmacy->name
                        . ' (' . $payment->subscriptionPlan->name . ')',
                    'time' => $payment->paid_at,
                ];
            });

        $activities = $pharmacies
            ->concat($customers)
            ->concat($subscriptions)
            ->sortByDesc('time')
            ->take(5)
            ->values();

        return [
            'activities' => $activities,

            'pendingSettlementAccounts' => SettlementAccount::withoutGlobalScopes()
                ->where('status', 'pending')
                ->count(),

            'unpaidSubscriptionPayments' => SubscriptionPayment::withoutGlobalScopes()
                ->where('status', 'unpaid')
                ->count(),
        ];
    }
}