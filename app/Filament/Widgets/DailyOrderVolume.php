<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class DailyOrderVolume extends ChartWidget
{
    protected ?string $heading = 'Daily Order Volume';
     protected static bool $isDiscovered = false;

    protected ?string $description = 'Number of orders received each day across the platform.';
protected static ?int $sort = 5;

protected int|string|array $columnSpan = [
    'lg' => 1,
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

            'labels' => $orders->pluck('date')
                ->map(
                    fn ($date) => \Carbon\Carbon::parse($date)->format('M d')
                )
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}