<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\SettlementsFilters;
use App\Filament\Widgets\SettlementsStatsOverview;
use App\Filament\Widgets\SettlementOverviewChart;
use App\Filament\Widgets\SettlementStatusChart;
use Filament\Pages\Page;
use App\Filament\Widgets\SettlementFlow;
use App\Filament\Widgets\SettlementsTable;

class Settlements extends Page
{
    public ?string $dateFrom = null;

    public ?string $dateUntil = null;

    protected static ?string $navigationLabel = 'Settlements';

    protected static ?string $title = 'Settlements';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.settlements';

    public function getSettlementFilters(): array
    {
        return [
            'dateFrom' => $this->dateFrom,
            'dateUntil' => $this->dateUntil,
        ];
    }

    public function updatedDateFrom(): void
    {
        $this->dispatch(
            'settlements-filters-updated',
            filters: $this->getSettlementFilters(),
        );
    }

    public function updatedDateUntil(): void
    {
        $this->dispatch(
            'settlements-filters-updated',
            filters: $this->getSettlementFilters(),
        );
    }

protected function getHeaderWidgets(): array
{
    return [
        SettlementsFilters::class,
        SettlementsStatsOverview::class,
        SettlementOverviewChart::class,
        SettlementStatusChart::class,
        SettlementFlow::class,
        SettlementsTable::class,
    ];
}
protected int | string | array $headerWidgetsColumns = [
    'default' => 1,
    'md' => 3,
    'xl' => 3,
];
}