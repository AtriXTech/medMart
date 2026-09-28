<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Pharmacy;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;

class TransactionsTable extends TableWidget
{
    protected static bool $isDiscovered = false;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::unifiedQuery()
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('gateway_reference')
                    ->label('Gateway Reference')
                    ->searchable()
                    ->placeholder('—'),

                \Filament\Tables\Columns\TextColumn::make('pharmacy.name')
                    ->label('Pharmacy'),

                \Filament\Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->default('Customer Order Payment'),

                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->money('NGN')
                    ->sortable(),

                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'paid' => 'Successful',
                        'unpaid' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                        default => ucfirst($state),
                    }),

                \Filament\Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable()
                      ->visibleFrom('md'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'paid' => 'Successful',
                        'unpaid' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                SelectFilter::make('type')
                    ->label('Transaction Type')
                    ->options([
                        'Customer Order Payment' => 'Customer Order Payment',
                        'Subscription Payment' => 'Subscription Payment',
                    ]),
                SelectFilter::make('pharmacy_id')
                    ->label('Pharmacy')
                    ->options(
                        Pharmacy::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->query(function ($query, array $data) {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->where(
                            'transactions.pharmacy_id',
                            $data['value']
                        );
                    }),
                Filter::make('created_at')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('from')
                            ->label('From'),

                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn($query, $date) =>
                                $query->whereDate('transactions.created_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn($query, $date) =>
                                $query->whereDate('transactions.created_at', '<=', $date)
                            );
                    }),
            ])
            ->headerActions([
                Action::make('refresh')
                    ->label('Refresh')
                    ->icon('heroicon-m-arrow-path')
                    ->action(fn() => $this->resetTable()),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Transaction Details')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(function (Transaction $record) {
                        return view(
                            'filament.pages.transaction-details',
                            [
                                'transaction' => $record,
                            ]
                        );
                    }),
            ]);
    }
}
