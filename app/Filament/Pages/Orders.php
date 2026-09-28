<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\OrderStatsOverview;
use App\Filament\Widgets\OrdersOverviewChart;
use App\Filament\Widgets\OrderStatusDistribution;
use App\Filament\Widgets\PharmacyOrderPerformance;
use Filament\Pages\Page;

class Orders extends Page
{
    protected static ?string $slug = 'orders';

    protected static ?string $title = 'Orders';

    protected string $view = 'filament.pages.orders';

protected function getHeaderWidgets(): array
{
    return [
        OrderStatsOverview::class,
        OrdersOverviewChart::class,
        OrderStatusDistribution::class,
        PharmacyOrderPerformance::class,
    ];
}
}