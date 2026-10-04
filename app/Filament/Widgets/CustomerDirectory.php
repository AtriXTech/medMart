<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Models\Customer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class CustomerDirectory extends BaseWidget
{
    protected static ?string $heading = 'Customer Directory';

    protected int|string|array $columnSpan = 'full';
    protected static bool $isDiscovered = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Customer::query()
                    ->withCount([
                        'pharmacies as linked_pharmacies_count',

                        'orders as total_orders_count' => function (Builder $query) {
                            $query->withoutGlobalScopes();
                        },

                        'orders as completed_orders_count' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Completed->value
                                );
                        },
                    ])
                    ->withSum([
                        'orders as total_spent' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    OrderStatus::Completed->value
                                );
                        },
                    ], 'total')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->state(function ($record): string {
                        return $record->deleted_at
                            ? 'Inactive'
                            : 'Active';
                    })
                    ->color(function ($state): string {
                        return $state === 'Active'
                            ? 'success'
                            : 'gray';
                    }),

                Tables\Columns\TextColumn::make('suspension')
                    ->label('Suspension')
                    ->badge()
                    ->state(function ($record): string {
                        $isSuspended = $record->pharmacyLinks()
                            ->where('is_suspended', true)
                            ->exists();

                        return $isSuspended
                            ? 'Suspended'
                            : '—';
                    })
                    ->color(function ($state): string {
                        return $state === 'Suspended'
                            ? 'danger'
                            : 'gray';
                    }),

                Tables\Columns\TextColumn::make('linked_pharmacies_count')
                    ->label('Linked')
                    ->formatStateUsing(function ($state): string {
                        $count = (int) $state;

                        return $count === 1
                            ? '1 Pharmacy'
                            : $count . ' Pharmacies';
                    })
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_orders_count')
                    ->label('Orders')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_spent')
                    ->label('Total Spent')
                    ->money('NGN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('id')
                    ->label('Action')
                    ->formatStateUsing(fn() => 'View')
                    ->url(fn($record) => url('/admin/customers/' . $record->id))
                    ->color('primary')
                    ->weight('medium'),
            ])

            ->defaultSort('total_orders_count', 'desc')
            ->paginated([10, 25, 50]);
    }
}
