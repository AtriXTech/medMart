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

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y · H:i')
                    ->timezone('Africa/Lagos')
                    ->sortable(),

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
                        fn($state): string => match ($state instanceof \App\Settlement\Enums\SettlementStatus
                            ? $state->value
                            : $state) {
                            'settled' => 'Settled',
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'failed' => 'Failed',
                            'reversed' => 'Reversed',
                            'on_hold' => 'On Hold',
                            'cancelled' => 'Cancelled',
                            default => ucfirst((string) $state),
                        }
                    )
                    ->color(
                        fn($state): string => match ($state instanceof \App\Settlement\Enums\SettlementStatus
                            ? $state->value
                            : $state) {
                            'settled' => 'success',
                            'pending' => 'warning',
                            'processing' => 'info',
                            'failed', 'reversed' => 'danger',
                            'on_hold' => 'warning',
                            'cancelled' => 'gray',
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
                        function (Settlement $record) {
                            $record->load(['pharmacy', 'settlementAccount']);

                            $record->setRelation(
                                'payments',
                                $record->payments()->withoutGlobalScopes()->orderBy('payments.id')->get()
                            );

                            return view(
                                'filament.widgets.settlement-details',
                                [
                                    'settlement' => $record,
                                ]
                            );
                        }
                    ),
                \Filament\Actions\Action::make('rebatch')
                    ->label('Re-batch')
                    ->icon('heroicon-m-arrow-path')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Re-batch settlement')
                    ->modalDescription(
                        'Eligible payments for this pharmacy are batched into a new settlement and a new transfer is dispatched.'
                    )
                    ->visible(
                        fn(Settlement $record): bool => in_array(
                            $record->status instanceof \App\Settlement\Enums\SettlementStatus
                                ? $record->status->value
                                : $record->status,
                            ['failed', 'reversed', 'cancelled'],
                            true
                        )
                    )
                    ->action(function (Settlement $record): void {
                        try {
                            $outcome = app(\App\Settlement\Services\SettlementAdminService::class)
                                ->retry($record->id, \Filament\Facades\Filament::auth()->id());
                        } catch (\App\Settlement\Exceptions\SettlementException $exception) {
                            \Filament\Notifications\Notification::make()
                                ->title('Unable to re-batch settlement')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        if ($outcome === 'rebatched') {
                            \Filament\Notifications\Notification::make()
                                ->title('Settlement re-batched.')
                                ->success()
                                ->send();

                            return;
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Nothing to settle')
                            ->body(app(\App\Settlement\Services\SettlementBatchService::class)->diagnose((int) $record->pharmacy_id))
                            ->warning()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
