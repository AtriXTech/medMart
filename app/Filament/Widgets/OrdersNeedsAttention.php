<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Pharmacy;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OrdersNeedsAttention extends BaseWidget
{
    protected static ?string $heading = 'Needs Attention';
     protected static bool $isDiscovered = false;
   protected static ?int $sort = 7;
protected int|string|array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pharmacy::query()
                    ->withCount([
                        'orders as cancelled_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Cancelled->value
                                );
                        },

                        'orders as processing_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->whereIn('status', [
                                    OrderStatus::Processing->value,
                                    OrderStatus::ReadyForPickup->value,
                                ]);
                        },

                        'orders as backlog_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->whereIn('status', [
                                    OrderStatus::PendingPayment->value,
                                    OrderStatus::Received->value,
                                ]);
                        },
                    ])
                    ->havingRaw(
                        'cancelled_orders > 0
             OR processing_orders > 0
             OR backlog_orders > 0'
                    )
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pharmacy')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('cancelled_orders')
                    ->label('High Cancellation')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(function ($state): string {
                        return (int) $state >= 5
                            ? 'danger'
                            : 'warning';
                    }),

                Tables\Columns\TextColumn::make('processing_orders')
                    ->label('Processing Delays')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(function ($state): string {
                        return (int) $state >= 5
                            ? 'danger'
                            : 'warning';
                    }),

                Tables\Columns\TextColumn::make('backlog_orders')
                    ->label('Order Backlog')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(function ($state): string {
                        return (int) $state >= 10
                            ? 'danger'
                            : 'warning';
                    }),
            ])
            ->defaultSort('cancelled_orders', 'desc')
            ->paginated([10, 25, 50]);
    }
}
