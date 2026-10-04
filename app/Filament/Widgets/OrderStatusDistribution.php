<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusDistribution extends ChartWidget
{
    protected ?string $heading = 'Orders by Status';
  
    protected ?string $description = 'Current distribution of platform orders.';
  protected static bool $isDiscovered = false;
  protected static ?int $sort = 3;

protected int|string|array $columnSpan = [
    'lg' => 1,
];

  protected function getData(): array
{
    $statuses = [
        OrderStatus::PendingPayment,
        OrderStatus::Paid,
        OrderStatus::Received,
        OrderStatus::Processing,
        OrderStatus::ReadyForPickup,
        OrderStatus::Completed,
        OrderStatus::Cancelled,
    ];

    $counts = Order::query()
        ->withoutGlobalScopes()
        ->selectRaw('status, COUNT(*) as total')
        ->groupBy('status')
        ->pluck('total', 'status');

    return [
        'datasets' => [
            [
                'label' => 'Orders',
                'data' => collect($statuses)
                    ->map(fn ($status) => $counts[$status->value] ?? 0)
                    ->toArray(),

                'backgroundColor' => [
                    '#F59E0B', // Pending Payment
                    '#2775E4', // Paid
                    '#06B6D4', // Received
                    '#8B5CF6', // Processing
                    '#08AEBC', // Ready for Pickup
                    '#16A34A', // Completed
                    '#DC2626', // Cancelled
                ],

                'borderWidth' => 0,
            ],
        ],

        'labels' => collect($statuses)
            ->map(fn ($status) => match ($status) {
                OrderStatus::PendingPayment => 'Pending Payment',
                OrderStatus::Paid => 'Paid',
                OrderStatus::Received => 'Received',
                OrderStatus::Processing => 'Processing',
                OrderStatus::ReadyForPickup => 'Ready for Pickup',
                OrderStatus::Completed => 'Completed',
                OrderStatus::Cancelled => 'Cancelled',
            })
            ->toArray(),
    ];
}

    protected function getType(): string
    {
        return 'doughnut';
    }
}