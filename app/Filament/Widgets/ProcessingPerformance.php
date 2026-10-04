<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class ProcessingPerformance extends ChartWidget
{
    protected ?string $heading = 'Processing Performance';
     protected static bool $isDiscovered = false;

    protected ?string $description = 'Current order distribution across the processing pipeline.';
protected static ?int $sort = 6;

protected int|string|array $columnSpan = [
    'lg' => 1,
];

    protected function getData(): array
    {
        $counts = Order::query()
            ->withoutGlobalScopes()
            ->selectRaw('status, COUNT(*) as total')
            ->whereIn('status', [
                OrderStatus::Received->value,
                OrderStatus::Processing->value,
                OrderStatus::ReadyForPickup->value,
                OrderStatus::Completed->value,
            ])
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => [
                        $counts[OrderStatus::Received->value] ?? 0,
                        $counts[OrderStatus::Processing->value] ?? 0,
                        $counts[OrderStatus::ReadyForPickup->value] ?? 0,
                        $counts[OrderStatus::Completed->value] ?? 0,
                    ],
                ],
            ],

            'labels' => [
                'Received',
                'Processing',
                'Ready for Pickup',
                'Completed',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}