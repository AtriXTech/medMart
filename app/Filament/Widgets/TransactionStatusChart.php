<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Livewire\Attributes\On;

class TransactionStatusChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Transaction Status';

    public array $transactionFilters = [];

#[On('transactions-filters-updated')]
public function updateTransactionFilters(array $filters): void
{
    $this->transactionFilters = $filters;

    $this->dispatch('$refresh');
}



   protected function getData(): array
{
    $filters = array_merge([
        'dateFrom' => null,
        'dateUntil' => null,
        'transactionType' => null,
        'transactionStatus' => null,
    ], $this->transactionFilters);

    $query = \App\Models\Transaction::unifiedQuery()
        ->when(
            $filters['dateFrom'],
            fn ($query, $date) =>
                $query->whereDate('transactions.created_at', '>=', $date)
        )
        ->when(
            $filters['dateUntil'],
            fn ($query, $date) =>
                $query->whereDate('transactions.created_at', '<=', $date)
        )
        ->when(
            $filters['transactionType'],
            fn ($query, $type) =>
                $query->where('transactions.type', $type)
        )
        ->when(
            $filters['transactionStatus'],
            fn ($query, $status) =>
                $query->where('transactions.status', $status)
        );

    $successful = (clone $query)
        ->where('transactions.status', 'paid')
        ->count();

    $pending = (clone $query)
        ->where('transactions.status', 'unpaid')
        ->count();

    $failed = (clone $query)
        ->where('transactions.status', 'failed')
        ->count();

    $refunded = (clone $query)
        ->where('transactions.status', 'refunded')
        ->count();

    return [
        'datasets' => [
            [
                'data' => [
                    $successful,
                    $pending,
                    $failed,
                    $refunded,
                ],
                'backgroundColor' => [
                    '#22C55E',
                    '#F59E0B',
                    '#EF4444',
                    '#8B5CF6',
                ],
            ],
        ],
        'labels' => [
            'Successful',
            'Pending',
            'Failed',
            'Refunded',
        ],
    ];
}


    protected function getType(): string
    {
        
        return 'doughnut';
    }
}