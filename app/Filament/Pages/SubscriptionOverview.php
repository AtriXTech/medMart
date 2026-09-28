<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Pages\Subscriptions;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionPayment;
use Filament\Pages\Page;

class SubscriptionOverview extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    // protected static ?string $slug = 'subscriptions/{subscription}';
    protected static ?string $slug = 'subscriptions/view/{subscription}';
    protected static ?string $title = 'Subscription Overview';

    protected string $view = 'filament.pages.subscription-overview';

    public Subscription $subscription;

    public function mount(Subscription $subscription): void
    {
        $this->subscription = $subscription->load([
            'pharmacy',
            'plan',
        ]);
    }

    public function debugPayments(): array
    {
        return SubscriptionPayment::query()
            ->where('pharmacy_id', $this->subscription->pharmacy_id)
            ->get([
                'id',
                'pharmacy_id',
                'subscription_plan_id',
                'reference',
                'status',
                'amount',
                'paid_at',
            ])
            ->toArray();
    }

    public function getPaymentHistory()
    {
        return SubscriptionPayment::withoutGlobalScopes()
            ->where('pharmacy_id', $this->subscription->pharmacy_id)
            ->latest('paid_at')
            ->get();
    }

    public function getTotalPaid(): float
    {
        return (float) SubscriptionPayment::withoutGlobalScopes()
            ->where('pharmacy_id', $this->subscription->pharmacy_id)
            ->where('status', PaymentStatus::Paid->value)
            ->sum('amount');
    }
   
 
    public function getBackUrl(): string
    {
        return Subscriptions::getUrl();
    }
}
