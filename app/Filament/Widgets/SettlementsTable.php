<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Settlement;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class SettlementsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Settlement::query()
                    ->with('pharmacy')
            )
            ->columns([
                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('pharmacy.name')
                    ->label('Pharmacy')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('NGN')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => match ($state) {
                            'settled' => 'Settled',
                            'pending' => 'Pending',
                            'failed' => 'Failed',
                            default => ucfirst($state),
                        }
                    )
                    ->color(
                        fn(string $state): string => match ($state) {
                            'settled' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y · H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'settled' => 'Settled',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                    ]),

                SelectFilter::make('pharmacy_id')
                    ->label('Pharmacy')
                    ->relationship('pharmacy', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From'),

                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(
                        function (
                            Builder $query,
                            array $data
                        ): Builder {
                            return $query
                                ->when(
                                    $data['from'] ?? null,
                                    fn(Builder $query, $date) =>
                                    $query->whereDate(
                                        'created_at',
                                        '>=',
                                        $date
                                    )
                                )
                                ->when(
                                    $data['until'] ?? null,
                                    fn(Builder $query, $date) =>
                                    $query->whereDate(
                                        'created_at',
                                        '<=',
                                        $date
                                    )
                                );
                        }
                    ),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->modalHeading('Settlement Details')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(
                        fn(Settlement $record) =>
                        view(
                            'filament.widgets.settlement-details',
                            [
                                'settlement' => $record->load([
                                    'pharmacy',
                                    'settlementAccount',
                                    'payments.order',
                                ]),
                            ]
                        )
                    )
            ])
            ->defaultSort('created_at', 'desc');
    }
}
