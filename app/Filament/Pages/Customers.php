<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CustomerActivityChart;
use App\Filament\Widgets\CustomerStatsOverview;
use App\Filament\Widgets\CustomersGrowthChart;
use Filament\Pages\Page;
use App\Filament\Widgets\CustomerDirectory;

class Customers extends Page
{
    protected static ?string $slug = 'customers';

    protected static ?string $title = 'Customers';

    protected string $view = 'filament.pages.customers';
protected int|array $headerWidgetsColumns = [
    'sm' => 1,
    'md' => 2,
    'lg' => 3,
];
   protected function getHeaderWidgets(): array
{
    return [
        CustomerStatsOverview::class,
        CustomersGrowthChart::class,
        CustomerActivityChart::class,
        CustomerDirectory::class,
    ];
}
}