<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionStatusChart extends ChartWidget
{
    protected  ?string $heading = 'Active Subscription Status';

    protected  ?string $maxHeight = '300px';
    protected static bool $isDiscovered = false;

    protected function getData(): array
    {
        $active = Subscription::query()
            ->where('status', SubscriptionStatus::Active->value)
            ->where(function ($query) {
                $query
                    ->whereNull('current_period_ends_at')
                    ->orWhere('current_period_ends_at', '>', now()->addDays(7));
            })
            ->count();

        $expiring = Subscription::query()
            ->where('status', SubscriptionStatus::Active->value)
            ->whereNotNull('current_period_ends_at')
            ->whereBetween('current_period_ends_at', [
                now(),
                now()->addDays(7),
            ])
            ->count();

        $cancelled = Subscription::query()
            ->where('status', SubscriptionStatus::Cancelled->value)
            ->count();

        return [
            'datasets' => [
                [
                    'data' => [
                        $active,
                        $expiring,
                        $cancelled,
                    ],
                    'backgroundColor' => [
                        '#2775E4',
                        '#F59E0B',
                        '#EF4444',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => [
                'Active',
                'Expiring',
                'Cancelled',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
