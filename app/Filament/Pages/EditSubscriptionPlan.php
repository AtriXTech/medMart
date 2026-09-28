<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\BillingInterval;
use App\Models\SubscriptionPlan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class EditSubscriptionPlan extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Edit Subscription Plan';

    protected static ?string $slug = 'subscriptions/plans/{plan}/edit';

    protected string $view = 'filament.pages.edit-subscription-plan';

    public SubscriptionPlan $plan;

    public string $name = '';

    public string $price = '';

    public string $billingInterval = 'monthly';

    public ?int $maxBranches = null;

    public ?int $maxStaff = null;

    public ?int $maxProducts = null;

    public bool $isActive = true;

    public array $allowedDurations = [];

    public function mount(SubscriptionPlan $plan): void
    {
        $this->plan = $plan;

        $this->name = $plan->name;
        $this->price = (string) $plan->price;
        $this->billingInterval = $plan->billing_interval->value;

        $this->maxBranches = $plan->max_branches;
        $this->maxStaff = $plan->max_staff;
        $this->maxProducts = $plan->max_products;

        $this->isActive = $plan->is_active;

        $this->allowedDurations = $plan->allowed_durations ?? [];
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'billingInterval' => ['required', 'in:monthly,yearly'],
            'maxBranches' => ['nullable', 'integer', 'min:0'],
            'maxStaff' => ['nullable', 'integer', 'min:0'],
            'maxProducts' => ['nullable', 'integer', 'min:0'],
            'allowedDurations' => ['nullable', 'array'],
            'allowedDurations.*' => ['required', 'string', 'max:50'],
        ]);

        $this->plan->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'price' => $this->price,
            'billing_interval' => BillingInterval::from($this->billingInterval),
            'max_branches' => $this->maxBranches,
            'max_staff' => $this->maxStaff,
            'max_products' => $this->maxProducts,
            'is_active' => $this->isActive,
            'allowed_durations' => $this->allowedDurations ?: null,
        ]);

        Notification::make()
            ->title('Subscription plan updated')
            ->body("{$this->name} has been updated successfully.")
            ->success()
            ->send();

        $this->redirect(
            SubscriptionPlans::getUrl()
        );
    }

    public function addDuration(): void
    {
        $this->allowedDurations[] = '';
    }

    public function removeDuration(int $index): void
    {
        unset($this->allowedDurations[$index]);

        $this->allowedDurations = array_values($this->allowedDurations);
    }
}