<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Filament\Widgets\ChartWidget;

class SubscriptionTrendChart extends ChartWidget
{
    protected ?string $heading = 'Subscription Trend';
protected static bool $isDiscovered = false;
    protected ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $months = match ($this->filter) {
            '3' => 3,
            '6' => 6,
            '24' => 24,
            default => 12,
        };

        $startDate = now()
            ->subMonths($months - 1)
            ->startOfMonth();

        $endDate = now()->endOfMonth();

        $subscriptions = Subscription::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw(
                'YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total'
            )
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($subscription) => sprintf(
                '%04d-%02d',
                $subscription->year,
                $subscription->month
            ));

        $labels = [];
        $data = [];

        $current = $startDate->copy();

        while ($current->lte($endDate)) {
            $key = $current->format('Y-m');

            $labels[] = $current->format('M Y');
            $data[] = (int) ($subscriptions[$key]->total ?? 0);

            $current->addMonth();
        }

        return [
            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'New Subscriptions',
                    'data' => $data,
                    'borderColor' => '#2775E4',
                    'backgroundColor' => 'rgba(39, 117, 228, 0.12)',
                    'fill' => true,
                    'tension' => 0.35,
                    'borderWidth' => 2,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            '3' => 'Last 3 Months',
            '6' => 'Last 6 Months',
            '12' => 'Last 12 Months',
            '24' => 'Last 24 Months',
        ];
    }
}