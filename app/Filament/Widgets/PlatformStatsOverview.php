<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Pharmacy;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsOverview extends StatsOverviewWidget
{
protected static ?int $sort = 2;
protected int|string|array $columnSpan = 'full';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pharmacies', Pharmacy::count())
                ->description('Registered pharmacies')
                ->descriptionIcon('heroicon-m-building-storefront'),

            Stat::make('Active Pharmacies', Pharmacy::where('status', 'active')->count())
                ->description('Currently active')
                ->descriptionIcon('heroicon-m-check-circle'),

            Stat::make('Total Customers', Customer::count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users'),

            Stat::make('Total Orders', Order::withoutGlobalScopes()->count())
                ->description('All platform orders')
                ->descriptionIcon('heroicon-m-shopping-bag'),

            Stat::make(
                'Revenue Today',
                '₦' . number_format(
                    Order::withoutGlobalScopes()
                        ->whereDate('created_at', today())
                        ->where('status', 'received')
                        ->sum('total'),
                    2
                )
            )
                ->description('Received orders today')
                ->descriptionIcon('heroicon-m-banknotes'),
        ];
    }
}
