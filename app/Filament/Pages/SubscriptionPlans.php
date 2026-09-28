<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\SubscriptionPlan;
use Filament\Pages\Page;
use App\Models\Subscription;
use Filament\Notifications\Notification;
// use App\Models\SubscriptionPlan;


class SubscriptionPlans extends Page
{

    protected static ?string $navigationLabel = 'Plans';

    protected static ?string $title = 'Subscription Plans';

    protected static ?string $slug = 'subscriptions/plans';

    protected string $view = 'filament.pages.subscription-plans';

    public function toggleStatus(int $planId): void
{
    $plan = SubscriptionPlan::findOrFail($planId);

    $plan->update([
        'is_active' => ! $plan->is_active,
    ]);

    Notification::make()
        ->title($plan->is_active ? 'Plan activated' : 'Plan deactivated')
        ->body(
            $plan->is_active
                ? "{$plan->name} is now active."
                : "{$plan->name} has been deactivated."
        )
        ->success()
        ->send();
}

public function deletePlan(int $planId): void
{
    $plan = SubscriptionPlan::findOrFail($planId);

    if ($plan->subscriptions()->exists()) {
        Notification::make()
            ->title('Plan cannot be deleted')
            ->body(
                "{$plan->name} has subscription records. Deactivate the plan instead."
            )
            ->danger()
            ->send();

        return;
    }

    $planName = $plan->name;

    $plan->delete();

    Notification::make()
        ->title('Plan deleted')
        ->body("{$planName} has been deleted successfully.")
        ->success()
        ->send();
}

public function getViewData(): array
{
    $plans = SubscriptionPlan::query()
        ->withCount([
            'subscriptions as subscribers_count' => function ($query) {
                $query->whereNotNull('pharmacy_id');
            },
        ])
        ->orderBy('price')
        ->get();

    return [
        'totalPlans' => SubscriptionPlan::count(),

        'activePlans' => SubscriptionPlan::where('is_active', true)->count(),

        'pharmaciesSubscribed' => Subscription::query()
            ->distinct()
            ->count('pharmacy_id'),

        'plans' => $plans,
    ];
}
}