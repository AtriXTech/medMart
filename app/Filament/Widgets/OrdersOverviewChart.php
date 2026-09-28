<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersOverviewChart extends ChartWidget
{
    protected ?string $heading = 'Orders Overview';
protected static bool $isDiscovered = false;
    protected ?string $description = 'Order activity across the platform.';

    protected int|string|array $columnSpan = [
        'lg' => 2,
    ];

    protected function getData(): array
    {
        $orders = Order::query()
            ->withoutGlobalScopes()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orders->pluck('total')->toArray(),
                ],
            ],
            'labels' => $orders->pluck('date')->map(
                fn ($date) => \Carbon\Carbon::parse($date)->format('M d')
            )->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}