<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Enums\BillingInterval;
use App\Models\SubscriptionPlan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;

class CreateSubscriptionPlan extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Create Subscription Plan';

    protected static ?string $slug = 'subscriptions/plans/create';

    protected string $view = 'filament.pages.create-subscription-plan';

    public string $name = '';

    public string $price = '';

    public string $billingInterval = 'monthly';

    public ?int $maxBranches = null;

    public ?int $maxStaff = null;

    public ?int $maxProducts = null;

    public bool $isActive = true;

    public array $allowedDurations = [];

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

        SubscriptionPlan::create([
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
            ->title('Subscription plan created')
            ->body("{$this->name} has been created successfully.")
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