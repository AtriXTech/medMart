<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\SubscriptionStatus;
use App\Filament\Widgets\SubscriptionStatusChart;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Filament\Widgets\SubscriptionTrendChart;

class Subscriptions extends Page
{
    use WithPagination;

    protected static ?string $navigationLabel = 'All Subscriptions';

    protected static ?string $title = 'Subscriptions';

    protected static ?string $slug = 'subscriptions';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.subscriptions';

    public string $search = '';

    public string $planFilter = '';

    public string $statusFilter = '';

    public string $billingFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'planFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'billingFilter' => ['except' => ''],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPlanFilter(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedBillingFilter(): void
    {
        $this->resetPage();
    }

    public function getTotalSubscriptions(): int
    {
        return Subscription::query()->count();
    }

    public function getActiveSubscriptions(): int
    {
        return Subscription::query()
            ->where('status', SubscriptionStatus::Active->value)
            ->count();
    }

    public function getExpiringSubscriptions(): int
    {
        return Subscription::query()
            ->where('status', SubscriptionStatus::Active->value)
            ->whereNotNull('current_period_ends_at')
            ->whereBetween('current_period_ends_at', [
                now(),
                now()->addDays(7),
            ])
            ->count();
    }

    public function getCancelledSubscriptions(): int
    {
        return Subscription::query()
            ->where('status', SubscriptionStatus::Cancelled->value)
            ->count();
    }

    #[Computed]
    public function subscriptions()
    {
        return Subscription::query()
            ->with(['pharmacy', 'plan'])
            ->when(
                $this->search !== '',
                function ($query) {
                    $query->whereHas('pharmacy', function ($pharmacyQuery) {
                        $pharmacyQuery
                            ->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    });
                }
            )
            ->when(
                $this->planFilter !== '',
                fn ($query) => $query->where(
                    'subscription_plan_id',
                    $this->planFilter
                )
            )
            ->when(
                $this->statusFilter !== '',
                fn ($query) => $query->where(
                    'status',
                    $this->statusFilter
                )
            )
            ->when(
                $this->billingFilter !== '',
                fn ($query) => $query->whereHas(
                    'plan',
                    fn ($planQuery) => $planQuery->where(
                        'billing_interval',
                        $this->billingFilter
                    )
                )
            )
            ->latest()
            ->paginate(10);
    }

    public function getPlans()
    {
        return SubscriptionPlan::query()
            ->orderBy('price')
            ->get();
    }

    public function getExpiringSubscriptionList()
    {
        return Subscription::query()
            ->with(['pharmacy', 'plan'])
            ->where('status', SubscriptionStatus::Active->value)
            ->whereNotNull('current_period_ends_at')
            ->whereBetween('current_period_ends_at', [
                now(),
                now()->addDays(7),
            ])
            ->orderBy('current_period_ends_at')
            ->get();
    }

    public function getCancelledSubscriptionList()
{
    return Subscription::query()
        ->with(['pharmacy', 'plan'])
        ->where('status', SubscriptionStatus::Cancelled->value)
        ->orderByDesc('cancelled_at')
        ->get();
}

   protected function getHeaderWidgets(): array
{
    return [
        SubscriptionStatusChart::class,
        SubscriptionTrendChart::class,
    ];
}
}