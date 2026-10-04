<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Models\Settlement;
use Filament\Widgets\Widget;
use Livewire\Attributes\On;

class SettlementFlow extends Widget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = 5;
protected int|string|array $columnSpan = 'full';


    protected string $view = 'filament.widgets.settlement-flow';

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
    }
    #[\Livewire\Attributes\On('refresh-settlements')]
public function refreshSettlements(): void
{
    $this->dispatch(
        'settlements-filters-updated',
        filters: $this->getSettlementFilters(),
    );
}

    public function getFlowData(): array
    {
        $paymentQuery = Payment::query()
            ->where('status', 'paid');

        $settlementQuery = Settlement::query();

        $dateFrom = $this->settlementFilters['dateFrom'] ?? null;
        $dateUntil = $this->settlementFilters['dateUntil'] ?? null;

        if ($dateFrom) {
            $paymentQuery->where(
                'paid_at',
                '>=',
                \Carbon\Carbon::parse($dateFrom)->startOfDay()
            );

            $settlementQuery->where(
                'created_at',
                '>=',
                \Carbon\Carbon::parse($dateFrom)->startOfDay()
            );
        }

        if ($dateUntil) {
            $paymentQuery->where(
                'paid_at',
                '<=',
                \Carbon\Carbon::parse($dateUntil)->endOfDay()
            );

            $settlementQuery->where(
                'created_at',
                '<=',
                \Carbon\Carbon::parse($dateUntil)->endOfDay()
            );
        }

        $revenueGenerated = $paymentQuery->sum('amount');

        $pendingSettlement = (clone $settlementQuery)
            ->where('status', 'pending')
            ->sum('amount');

        $settledCredited = (clone $settlementQuery)
            ->where('status', 'settled')
            ->sum('amount');

        return [
            'revenueGenerated' => (float) $revenueGenerated,
            'pendingSettlement' => (float) $pendingSettlement,
            'settledCredited' => (float) $settledCredited,
        ];
    }
}