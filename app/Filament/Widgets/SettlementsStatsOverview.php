<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Settlement;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class SettlementsStatsOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

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

        $this->dispatch('$refresh');
    }

    protected function getStats(): array
    {
        $query = Settlement::query();

        $dateFrom = $this->settlementFilters['dateFrom'] ?? null;
        $dateUntil = $this->settlementFilters['dateUntil'] ?? null;

        if ($dateFrom) {
            $query->where(
                'created_at',
                '>=',
                Carbon::parse($dateFrom)->startOfDay()
            );
        }

        if ($dateUntil) {
            $query->where(
                'created_at',
                '<=',
                Carbon::parse($dateUntil)->endOfDay()
            );
        }

        $totalSettled = (clone $query)
            ->where('status', 'settled')
            ->sum('amount');

        $pendingQuery = (clone $query)
            ->where('status', 'pending');

        $pendingSettlement = $pendingQuery->sum('amount');

        $pendingCount = $pendingQuery->count();

        $todayQuery = Settlement::query()
            ->where('status', 'settled')
            ->whereBetween('processed_at', [
                now()->startOfDay(),
                now()->endOfDay(),
            ]);

        $todaySettlement = $todayQuery->sum('amount');

        $todayCount = $todayQuery->count();

        $lastSettlement = Settlement::query()
            ->where('status', 'settled')
            ->whereNotNull('processed_at')
            ->latest('processed_at')
            ->first();

        $lastSettlementText = $lastSettlement?->processed_at
            ? $lastSettlement->processed_at->format('M j, Y · H:i')
            : 'No settlement yet';

        return [
            Stat::make(
                'Total Settled',
                '₦' . number_format((float) $totalSettled, 2)
            )
                ->description('Successfully credited')
                ->descriptionIcon('heroicon-m-check-circle'),

            Stat::make(
                'Pending Settlement',
                '₦' . number_format((float) $pendingSettlement, 2)
            )
                ->description(
                    $pendingCount . ' settlement' .
                    ($pendingCount === 1 ? '' : 's')
                )
                ->descriptionIcon('heroicon-m-clock'),

            Stat::make(
                "Today's Settlement",
                '₦' . number_format((float) $todaySettlement, 2)
            )
                ->description(
                    $todayCount . ' settlement' .
                    ($todayCount === 1 ? '' : 's')
                )
                ->descriptionIcon('heroicon-m-calendar-days'),

            Stat::make(
                'Last Settlement',
                $lastSettlement
                    ? '₦' . number_format((float) $lastSettlement->amount, 2)
                    : '₦0.00'
            )
                ->description($lastSettlementText)
                ->descriptionIcon('heroicon-m-banknotes'),
        ];
    }
}