<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Settlement;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class SettlementStatusChart extends ChartWidget
{
    protected static bool $isDiscovered = false;


    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
    ];

    protected ?string $heading = 'Settlement Status';

    public array $settlementFilters = [
        'dateFrom' => null,
        'dateUntil' => null,
    ];

    #[On('settlements-filters-updated')]
    public function updateFilters(array $filters): void
    {
        $this->settlementFilters = array_merge(
            $this->settlementFilters,
            $filters,
        );

        $this->updateChartData();
    }

    protected function getData(): array
    {
        $query = Settlement::query();

        $dateFrom = $this->settlementFilters['dateFrom'] ?? null;
        $dateUntil = $this->settlementFilters['dateUntil'] ?? null;

        if ($dateFrom) {
            $query->where(
                'created_at',
                '>=',
                \Carbon\Carbon::parse($dateFrom)->startOfDay()
            );
        }

        if ($dateUntil) {
            $query->where(
                'created_at',
                '<=',
                \Carbon\Carbon::parse($dateUntil)->endOfDay()
            );
        }

        $settled = (clone $query)
            ->where('status', 'settled')
            ->sum('amount');

        $pending = (clone $query)
            ->where('status', 'pending')
            ->sum('amount');

        $failed = (clone $query)
            ->where('status', 'failed')
            ->sum('amount');

        return [
            'datasets' => [
                [
                    'data' => [
                        (float) $settled,
                        (float) $pending,
                        (float) $failed,
                    ],
                    'backgroundColor' => [
                        '#2775E4', // Settled
                        '#F59E0B', // Pending
                        '#DC2626', // Failed
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => [
                'Settled',
                'Pending',
                'Failed',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
