<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CustomerActivityChart;
use App\Filament\Widgets\CustomerStatsOverview;
use App\Filament\Widgets\CustomersGrowthChart;
use Filament\Pages\Page;

class Customers extends Page
{
    protected static ?string $slug = 'customers';

    protected static ?string $title = 'Customers';

    protected string $view = 'filament.pages.customers';

    protected function getHeaderWidgets(): array
    {
        return [
            CustomerStatsOverview::class,
            CustomersGrowthChart::class,
            CustomerActivityChart::class,
        ];
    }
}