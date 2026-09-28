<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrdersOverview extends ChartWidget
{  protected static bool $isDiscovered = false;
    protected  ?string $heading = 'Orders Overview';

    protected  ?string $description = 'Orders over the last 7 days';
    protected static ?int $sort = 3;
  

protected int | string | array $columnSpan = [
    'md' => 6, // 6 out of 12 columns (exactly 50% width)
];

    protected function getData(): array
    {
        $startDate = Carbon::today()->subDays(6);
        $endDate = Carbon::today();

        $orders = Order::withoutGlobalScopes()
            ->whereBetween('created_at', [
                $startDate->startOfDay(),
                $endDate->endOfDay(),
            ])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $labels = [];
        $data = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $key = $date->format('Y-m-d');

            $labels[] = $date->format('D');
            $data[] = $orders[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}