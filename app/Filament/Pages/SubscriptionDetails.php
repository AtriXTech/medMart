<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\PaymentStatus;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use Filament\Pages\Page;

class SubscriptionDetails extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Subscription Details';

    protected static ?string $slug = 'subscriptions/plans/subscription/{subscription}';

    protected string $view = 'filament.pages.subscription-details';

    public Subscription $subscription;

    public SubscriptionPlan $plan;

    public function mount(Subscription $subscription): void
    {
        $this->subscription = $subscription->load([
            'pharmacy',
            'plan',
        ]);

        $this->plan = $this->subscription->plan;
    }

   public function getViewData(): array
{
    $pharmacyId = $this->subscription->pharmacy_id;

    $payments = SubscriptionPayment::withoutGlobalScopes()
        ->where('pharmacy_id', $pharmacyId)
        ->latest('paid_at')
        ->get();

    $paidPayments = $payments->filter(
        fn (SubscriptionPayment $payment) =>
            $payment->status === PaymentStatus::Paid
    );

    $totalLifetimePaid = SubscriptionPayment::withoutGlobalScopes()
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', PaymentStatus::Paid->value)
        ->sum('amount');

    $successfulPaymentCount = SubscriptionPayment::withoutGlobalScopes()
        ->where('pharmacy_id', $pharmacyId)
        ->where('status', PaymentStatus::Paid->value)
        ->count();

    return [
        'payments' => $payments,
        'successfulPayments' => $paidPayments,
        'totalLifetimePaid' => $totalLifetimePaid,
        'successfulPaymentCount' => $successfulPaymentCount,
    ];
}
}