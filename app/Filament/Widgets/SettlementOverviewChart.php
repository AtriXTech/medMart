<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Settlement;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class SettlementOverviewChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 2,
    ];

    protected ?string $heading = 'Settlement Value';

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
        $query = Settlement::query()
            ->where('status', 'settled');

        $dateFrom = $this->settlementFilters['dateFrom'] ?? null;
        $dateUntil = $this->settlementFilters['dateUntil'] ?? null;

        if ($dateFrom) {
            $query->where(
                'processed_at',
                '>=',
                Carbon::parse($dateFrom)->startOfDay()
            );
        }

        if ($dateUntil) {
            $query->where(
                'processed_at',
                '<=',
                Carbon::parse($dateUntil)->endOfDay()
            );
        }

        $settlements = $query
            ->orderBy('processed_at')
            ->get();

        $labels = [];
        $values = [];

        foreach ($settlements as $settlement) {
            $labels[] = $settlement->processed_at?->format('M j') ?? '—';
            $values[] = (float) $settlement->amount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Settlement Value',
                    'data' => $values,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
