<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Widgets\ChartWidget;

class CustomersGrowthChart extends ChartWidget
{
    protected ?string $heading = 'Customer Growth';
       protected static ?int $sort = 2; // Appears second
protected static bool $isDiscovered = false;
protected int | string | array $columnSpan = [
    'md' => 6, // 6 out of 12 columns (exactly 50% width)
];

    protected ?string $description =
        'Total registered platform customers over time';


    protected function getFilters(): ?array
    {
        return [
            '7d' => '7D',
            '30d' => '30D',
            '90d' => '90D',
            '12m' => '12M',
        ];
    }

    protected function getData(): array
    {
        $filter = $this->filter ?? '30d';

        if ($filter === '12m') {
            return $this->getMonthlyData();
        }

        $days = match ($filter) {
            '7d' => 6,
            '30d' => 29,
            '90d' => 89,
            default => 29,
        };

        $start = now()->subDays($days)->startOfDay();
        $end = now()->startOfDay();

        $startingCustomers = Customer::query()
            ->where('created_at', '<', $start)
            ->count();

        $customers = Customer::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $data = [];
        $runningTotal = $startingCustomers;

        $current = $start->copy();

        while ($current <= $end) {
            $date = $current->toDateString();

            $runningTotal += $customers[$date]->total ?? 0;

            $labels[] = $current->format('M d');
            $data[] = $runningTotal;

            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Customers',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getMonthlyData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $startingCustomers = Customer::query()
            ->where('created_at', '<', $start)
            ->count();

        $customers = Customer::query()
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels = [];
        $data = [];
        $runningTotal = $startingCustomers;

        $current = $start->copy();

        for ($i = 0; $i < 12; $i++) {
            $month = $current->format('Y-m');

            $runningTotal += $customers[$month]->total ?? 0;

            $labels[] = $current->format('M Y');
            $data[] = $runningTotal;

            $current->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Customers',
                    'data' => $data,
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