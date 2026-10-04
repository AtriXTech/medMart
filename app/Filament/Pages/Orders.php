<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\OrderStatsOverview;
use App\Filament\Widgets\OrdersOverviewChart;
use App\Filament\Widgets\OrderStatusDistribution;
use App\Filament\Widgets\PharmacyOrderPerformance;
use App\Filament\Widgets\DailyOrderVolume;
use App\Filament\Widgets\ProcessingPerformance;
use App\Filament\Widgets\OrdersNeedsAttention;
use Filament\Pages\Page;

class Orders extends Page
{
    protected static ?string $slug = 'orders';

    protected static ?string $title = 'Orders';

    protected string $view = 'filament.pages.orders';

protected int|array $headerWidgetsColumns = [
    'sm' => 1,
    'md' => 2,
    'lg' => 3,
];

protected function getHeaderWidgets(): array
{
    return [
        OrderStatsOverview::class,
        OrdersOverviewChart::class,
        OrderStatusDistribution::class,
        PharmacyOrderPerformance::class,
        DailyOrderVolume::class,
        ProcessingPerformance::class,
        OrdersNeedsAttention::class,
    ];
}
}