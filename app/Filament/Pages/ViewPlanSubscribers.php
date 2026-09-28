<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Filament\Pages\Page;
use Livewire\WithPagination;

class ViewPlanSubscribers extends Page
{
    use WithPagination;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Plan Subscribers';

    protected static ?string $slug = 'subscriptions/plans/{plan}/subscribers';

    protected string $view = 'filament.pages.view-plan-subscribers';

    public SubscriptionPlan $plan;

    public string $search = '';

    public string $status = '';

    public string $dateFrom = '';

    public string $dateTo = '';

    public function mount(SubscriptionPlan $plan): void
    {
        $this->plan = $plan;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset([
            'search',
            'status',
            'dateFrom',
            'dateTo',
        ]);

        $this->resetPage();
    }

    public function getSubscribers()
    {
        return Subscription::query()
            ->with('pharmacy')
            ->where('subscription_plan_id', $this->plan->id)
            ->when(
                $this->search,
                fn ($query) => $query->whereHas(
                    'pharmacy',
                    fn ($pharmacyQuery) => $pharmacyQuery
                        ->where('name', 'like', '%' . $this->search . '%')
                )
            )
            ->when(
                $this->status,
                fn ($query) => $query->where('status', $this->status)
            )
            ->when(
                $this->dateFrom,
                fn ($query) => $query->whereDate(
                    'current_period_starts_at',
                    '>=',
                    $this->dateFrom
                )
            )
            ->when(
                $this->dateTo,
                fn ($query) => $query->whereDate(
                    'current_period_starts_at',
                    '<=',
                    $this->dateTo
                )
            )
            ->latest('current_period_starts_at')
            ->paginate(10);
    }

    public function getViewData(): array
    {
        return [
            'subscribers' => $this->getSubscribers(),
            'statuses' => SubscriptionStatus::cases(),
        ];
    }
}