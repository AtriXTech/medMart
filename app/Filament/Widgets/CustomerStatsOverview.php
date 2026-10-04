<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomerStatsOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;
      protected static ?int $sort = 1;
protected int|string|array $columnSpan = 'full';


    protected function getStats(): array
    {
        // Total registered customers on the platform.
        $totalCustomers = Customer::query()->count();

        // Customers who placed at least one non-cancelled order
        // within the last 30 days.
        $activeCustomers = Customer::query()
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('orders')
                    ->whereColumn('orders.customer_id', 'customers.id')
                    ->where('orders.status', '!=', OrderStatus::Cancelled->value)
                    ->where('orders.created_at', '>=', now()->subDays(30));
            })
            ->count();

        // Customers registered this month.
        $newThisMonth = Customer::query()
            ->whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
            ->count();

        // Total non-cancelled orders across the whole platform.
        // withoutGlobalScopes() is important because Order has
        // the pharmacy tenancy scope.
        $totalOrders = Order::query()
            ->withoutGlobalScopes()
            ->where('status', '!=', OrderStatus::Cancelled->value)
            ->count();

        // Average non-cancelled orders per registered customer.
        $ordersPerCustomer = $totalCustomers > 0
            ? round($totalOrders / $totalCustomers, 1)
            : 0;

        return [
            Stat::make(
                'Total Customers',
                number_format($totalCustomers)
            )
                ->description('Registered platform customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make(
                'Active Customers',
                number_format($activeCustomers)
            )
                ->description('Active in the last 30 days')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make(
                'New This Month',
                number_format($newThisMonth)
            )
                ->description('New customer registrations')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color('info'),

            Stat::make(
                'Orders / Customer',
                number_format($ordersPerCustomer, 1)
            )
                ->description('Average non-cancelled orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),
        ];
    }
}