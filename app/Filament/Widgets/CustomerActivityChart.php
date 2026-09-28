<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\ChartWidget;

class CustomerActivityChart extends ChartWidget
{
    protected ?string $heading = 'Customer Activity';

    protected ?string $description =
        'Active engagement vs new signups';
        protected static bool $isDiscovered = false;

     protected static ?int $sort = 2; // Appears second

protected int | string | array $columnSpan = [
    'md' => 6, // 6 out of 12 columns (exactly 50% width)
];

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

        $newCustomers = Customer::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereBetween('created_at', [$start, now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $activeCustomers = Order::query()
            ->withoutGlobalScopes()
            ->selectRaw(
                'DATE(created_at) as date, COUNT(DISTINCT customer_id) as total'
            )
            ->whereBetween('created_at', [$start, now()])
            ->where('status', '!=', OrderStatus::Cancelled->value)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $newData = [];
        $activeData = [];

        $current = $start->copy();

        while ($current <= $end) {
            $date = $current->toDateString();

            $labels[] = $current->format('M d');
            $newData[] = $newCustomers[$date]->total ?? 0;
            $activeData[] = $activeCustomers[$date]->total ?? 0;

            $current->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Signups',
                    'data' => $newData,
                ],
                [
                    'label' => 'Active Customers',
                    'data' => $activeData,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getMonthlyData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $newCustomers = Customer::query()
            ->selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total"
            )
            ->where('created_at', '>=', $start)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $activeCustomers = Order::query()
            ->withoutGlobalScopes()
            ->selectRaw(
                "DATE_FORMAT(created_at, '%Y-%m') as month,
                 COUNT(DISTINCT customer_id) as total"
            )
            ->where('created_at', '>=', $start)
            ->where('status', '!=', OrderStatus::Cancelled->value)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $labels = [];
        $newData = [];
        $activeData = [];

        $current = $start->copy();

        for ($i = 0; $i < 12; $i++) {
            $month = $current->format('Y-m');

            $labels[] = $current->format('M Y');
            $newData[] = $newCustomers[$month]->total ?? 0;
            $activeData[] = $activeCustomers[$month]->total ?? 0;

            $current->addMonth();
        }

        return [
            'datasets' => [
                [
                    'label' => 'New Signups',
                    'data' => $newData,
                ],
                [
                    'label' => 'Active Customers',
                    'data' => $activeData,
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