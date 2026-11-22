<?php

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\SubscriptionPayment;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Widgets\TransactionsStatsOverview;
use App\Filament\Widgets\TransactionOverviewChart;
use App\Filament\Widgets\TransactionStatusChart;
use App\Filament\Widgets\TransactionsTable;
use Filament\Forms\Components\DatePicker;
use App\Filament\Widgets\TransactionsFilters;

class Transactions extends Page
{
    // protected static ?string $navigationGroup = 'Finance';
    public ?string $dateFrom = null;
    public ?string $dateUntil = null;
    public ?string $transactionType = null;
    public ?string $transactionStatus = null;
    protected static ?string $navigationLabel = 'Transactions';
    protected static ?string $title = 'Transactions';
    protected static ?int $navigationSort = 1;


    protected string $view = 'filament.pages.transactions';

    public function getTransactionFilters(): array
    {
        return [
            'dateFrom' => $this->dateFrom,
            'dateUntil' => $this->dateUntil,
            'transactionType' => $this->transactionType,
            'transactionStatus' => $this->transactionStatus,
        ];
    }


    public function getTransactions(): Collection
    {
        $payments = Payment::withoutGlobalScopes()
            ->with('pharmacy')
            ->get()
            ->map(function (Payment $payment) {
                return [
                    'reference' => $payment->reference,
                    'pharmacy' => $payment->pharmacy?->name ?? '—',
                    'pharmacy_id' => $payment->pharmacy_id,
                    'type' => 'Customer Order Payment',
                    'amount' => $payment->amount,
                    'status' => $payment->status->value,
                    'gateway_reference' => $payment->paystack_reference,
                    'created_at' => $payment->created_at,
                ];
            });

        $subscriptionPayments = SubscriptionPayment::withoutGlobalScopes()
            ->with('pharmacy')
            ->get()
            ->map(function (SubscriptionPayment $payment) {
                return [
                    'reference' => $payment->reference,
                    'pharmacy' => $payment->pharmacy?->name ?? '—',
                    'pharmacy_id' => $payment->pharmacy_id,
                    'type' => 'Subscription Payment',
                    'amount' => $payment->amount,
                    'status' => $payment->status->value,
                    'gateway_reference' => null,
                    'created_at' => $payment->created_at,
                ];
            });

        return $payments
            ->concat($subscriptionPayments)
            ->filter(function (array $transaction) {
                if ($this->dateFrom && $transaction['created_at']->lt(
                    \Carbon\Carbon::parse($this->dateFrom)->startOfDay()
                )) {
                    return false;
                }

                if ($this->dateUntil && $transaction['created_at']->gt(
                    \Carbon\Carbon::parse($this->dateUntil)->endOfDay()
                )) {
                    return false;
                }

                if (
                    $this->transactionType &&
                    $transaction['type'] !== $this->transactionType
                ) {
                    return false;
                }

                if (
                    $this->transactionStatus &&
                    $transaction['status'] !== $this->transactionStatus
                ) {
                    return false;
                }

                return true;
            })
            ->sortByDesc('created_at')
            ->values();
    }


    public function updatedDateFrom(): void
    {
        $this->dispatch(
            'transactions-filters-updated',
            filters: $this->getTransactionFilters()
        );
    }

    public function updatedDateUntil(): void
    {
        $this->dispatch(
            'transactions-filters-updated',
            filters: $this->getTransactionFilters()
        );
    }

    public function updatedTransactionType(): void
    {
        $this->dispatch(
            'transactions-filters-updated',
            filters: $this->getTransactionFilters()
        );
    }

    public function updatedTransactionStatus(): void
    {
        $this->dispatch(
            'transactions-filters-updated',
            filters: $this->getTransactionFilters()
        );
    }
    protected function getHeaderWidgets(): array
    {
        return [
              TransactionsFilters::class,
            TransactionsStatsOverview::class,
            TransactionOverviewChart::class,
            TransactionStatusChart::class,
            TransactionsTable::class,
        ];
    }
}
