<?php

namespace App\Filament\Widgets;

use App\Models\Pharmacy;
use Filament\Widgets\ChartWidget;

class PharmacyGrowthChart extends ChartWidget
{
    protected  ?string $heading = 'Pharmacy Growth';

    protected  ?string $description = 'New merchant onboardings';
 
  protected static ?int $sort = 3;
protected int|string|array $columnSpan = [
    'lg' => 1,
];

    protected function getData(): array
    {
        $pharmacies = Pharmacy::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'New Pharmacies',
                    'data' => $pharmacies->pluck('total')->toArray(),
                ],
            ],
            'labels' => $pharmacies->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}