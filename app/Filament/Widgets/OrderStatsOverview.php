<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;
    protected function getStats(): array
    {
        $totalOrders = Order::query()
            ->withoutGlobalScopes()
            ->count();

        $pendingOrders = Order::query()
            ->withoutGlobalScopes()
            ->where('status', OrderStatus::PendingPayment->value)
            ->count();

        $processedOrders = Order::query()
            ->withoutGlobalScopes()
            ->whereIn('status', [
                OrderStatus::Processing->value,
                OrderStatus::ReadyForPickup->value,
                OrderStatus::Completed->value,
            ])
            ->count();

        $completedOrders = Order::query()
            ->withoutGlobalScopes()
            ->where('status', OrderStatus::Completed->value)
            ->count();

        $cancelledOrders = Order::query()
            ->withoutGlobalScopes()
            ->where('status', OrderStatus::Cancelled->value)
            ->count();

        $completionBase = $completedOrders + $cancelledOrders;

        $completionRate = $completionBase > 0
            ? round(($completedOrders / $completionBase) * 100, 1)
            : 0;

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('All platform orders')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Pending Orders', number_format($pendingOrders))
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Processed Orders', number_format($processedOrders))
                ->description('Processing or beyond')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Completed Orders', number_format($completedOrders))
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Cancelled Orders', number_format($cancelledOrders))
                ->description('Cancelled orders')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description('Completed vs completed + cancelled')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('success'),
        ];
    }
}