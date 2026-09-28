<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class TransactionsStatsOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;
    public array $transactionFilters = [];
    #[On('transactions-filters-updated')]
    public function updateTransactionFilters(array $filters): void
    {
        $this->transactionFilters = $filters;

        $this->dispatch('$refresh');
    }

    protected function getFilteredTransactions()
    {
        $query = \App\Models\Transaction::unifiedQuery();

        $filters = $this->transactionFilters;

        return $query
            ->when(
                $filters['dateFrom'] ?? null,
                fn($query, $date) =>
                $query->whereDate('transactions.created_at', '>=', $date)
            )
            ->when(
                $filters['dateUntil'] ?? null,
                fn($query, $date) =>
                $query->whereDate('transactions.created_at', '<=', $date)
            )
            ->when(
                $filters['transactionType'] ?? null,
                fn($query, $type) =>
                $query->where('transactions.type', $type)
            )
            ->when(
                $filters['transactionStatus'] ?? null,
                fn($query, $status) =>
                $query->where('transactions.status', $status)
            );
    }
    protected function getStats(): array
    {
        $totalTransactionValue = (float) $this->getFilteredTransactions()->sum('amount');

        $transactionCount = $this->getFilteredTransactions()->count();

        $successfulTransactions = $this->getFilteredTransactions()
            ->where('status', 'paid')
            ->count();

        $pendingTransactions = $this->getFilteredTransactions()
            ->where('status', 'unpaid')
            ->count();

        $failedTransactions = $this->getFilteredTransactions()
            ->where('status', 'failed')
            ->count();

        $refundedTransactions = $this->getFilteredTransactions()
            ->where('status', 'refunded')
            ->count();

        return [
            Stat::make(
                'Total Transaction Value',
                '₦' . number_format((float) $totalTransactionValue, 2)
            )
                ->extraAttributes([
                    'class' => 'whitespace-nowrap',
                ]),

            Stat::make(
                'Successful Transactions',
                $successfulTransactions
            ),

            Stat::make(
                'Transaction Count',
                $transactionCount
            ),

            Stat::make(
                'Pending Transactions',
                $pendingTransactions
            ),

            Stat::make(
                'Failed Transactions',
                $failedTransactions
            ),

            Stat::make(
                'Refunded Transactions',
                $refundedTransactions
            ),


        ];
    }

    protected function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'lg' => 3,
        ];
    }
}
