<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Pharmacy;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PharmacyOrderPerformance extends BaseWidget
{
     protected static bool $isDiscovered = false;
    protected static ?string $heading = 'Pharmacy Order Performance';
  protected static ?int $sort = 4;

protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pharmacy::query()
                    ->withCount([
                        'orders as received_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Received->value
                                );
                        },

                        'orders as processed_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->whereIn('status', [
                                    OrderStatus::Processing->value,
                                    OrderStatus::ReadyForPickup->value,
                                    OrderStatus::Completed->value,
                                ]);
                        },

                        'orders as completed_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Completed->value
                                );
                        },

                        'orders as cancelled_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Cancelled->value
                                );
                        },
                    ])
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pharmacy')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('received_orders')
                    ->label('Received')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('processed_orders')
                    ->label('Processed')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_orders')
                    ->label('Completed')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cancelled_orders')
                    ->label('Cancelled')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completion_rate')
                    ->label('Rate')
                    ->state(function ($record): string {
                        $completed = (int) $record->completed_orders;
                        $cancelled = (int) $record->cancelled_orders;

                        $base = $completed + $cancelled;

                        if ($base === 0) {
                            return '—';
                        }

                        return round(
                            ($completed / $base) * 100,
                            1
                        ) . '%';
                    })
                    ->badge()
                    ->color(function ($record): string {
                        $completed = (int) $record->completed_orders;
                        $cancelled = (int) $record->cancelled_orders;

                        $base = $completed + $cancelled;

                        if ($base === 0) {
                            return 'gray';
                        }

                        $rate = ($completed / $base) * 100;

                        return match (true) {
                            $rate >= 90 => 'success',
                            $rate >= 70 => 'warning',
                            default => 'danger',
                        };
                    }),

                Tables\Columns\TextColumn::make('id')
                    ->label('Action')
                    ->formatStateUsing(
                        fn () => 'View'
                    )
                    ->url(
                        fn ($record) =>
                            '/admin/orders/pharmacy/' . $record->id
                    )
                    ->color('primary')
                    ->weight('medium'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                    ]),
            ])
            ->defaultSort('name')
            ->paginated([10, 25, 50]);
    }
}