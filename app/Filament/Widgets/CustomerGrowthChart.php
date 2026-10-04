<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomerGrowthChart extends ChartWidget
{
    protected  ?string $heading = 'Customer Growth';

    protected  ?string $description = 'New user signups';
  
  protected static ?int $sort = 4;
protected int|string|array $columnSpan = [
    'lg' => 1,
];

    protected function getData(): array
    {
        $customers = Customer::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'New Customers',
                    'data' => $customers->pluck('total')->toArray(),
                ],
            ],
            'labels' => $customers->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}