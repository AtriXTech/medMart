<?php

namespace App\Filament\Widgets;

use Livewire\Attributes\On;
use Filament\Widgets\ChartWidget;

class TransactionOverviewChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected ?string $heading = 'Transaction Overview';
    public array $transactionFilters = [];
    #[On('transactions-filters-updated')]
    public function updateTransactionFilters(array $filters): void
    {
        $this->transactionFilters = $filters;

        $this->updateChartData();
    }

    
    protected function getData(): array
    {
        $statuses = [
            'paid' => 'Successful',
            'unpaid' => 'Pending',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
        ];

        $filters = $this->transactionFilters;

        $query = \App\Models\Transaction::unifiedQuery()
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

        $transactions = $query
            ->get()
            ->groupBy(function ($transaction) {
                return \Carbon\Carbon::parse($transaction->created_at)
                    ->format('Y-m-d');
            });

        $startDate = ! empty($filters['dateFrom'])
            ? \Carbon\Carbon::parse($filters['dateFrom'])->startOfDay()
            : null;

        $endDate = ! empty($filters['dateUntil'])
            ? \Carbon\Carbon::parse($filters['dateUntil'])->endOfDay()
            : null;

        if (! $startDate) {
            $firstTransaction = $transactions->keys()->sort()->first();

            $startDate = $firstTransaction
                ? \Carbon\Carbon::parse($firstTransaction)->startOfDay()
                : now()->startOfDay();
        }

        if (! $endDate) {
            $lastTransaction = $transactions->keys()->sort()->last();

            $endDate = $lastTransaction
                ? \Carbon\Carbon::parse($lastTransaction)->endOfDay()
                : now()->endOfDay();
        }

        $labels = [];
        $datasets = [];

        foreach ($statuses as $status => $label) {
            $datasets[$status] = [];
        }

        for (
            $date = $startDate->copy();
            $date->lte($endDate);
            $date->addDay()
        ) {
            $dateString = $date->format('Y-m-d');

            $labels[] = $date->format('M d');

            $dayTransactions = $transactions->get($dateString, collect());

            foreach ($statuses as $status => $label) {
                $datasets[$status][] = (float) $dayTransactions
                    ->where('status', $status)
                    ->sum('amount');
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Successful',
                    'data' => $datasets['paid'],
                ],
                [
                    'label' => 'Pending',
                    'data' => $datasets['unpaid'],
                ],
                [
                    'label' => 'Failed',
                    'data' => $datasets['failed'],
                ],
                [
                    'label' => 'Refunded',
                    'data' => $datasets['refunded'],
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
