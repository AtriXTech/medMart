<?php

namespace App\Filament\Resources\Pharmacies\Tables;

use App\Enums\OrderStatus;
use App\Enums\PharmacyStatus;
use App\Models\SubscriptionPlan;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PharmaciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $query
                    ->with(['subscription.plan'])

                    ->withCount([
                        'orders as total_orders' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->where(
                                    'status',
                                    '!=',
                                    OrderStatus::Cancelled->value
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
                    ])

                    ->withSum([
                        'orders as revenue' => function (Builder $query) {
                            $query
                                ->withoutGlobalScopes()
                                ->whereIn('status', [
                                    OrderStatus::Paid->value,
                                    OrderStatus::Received->value,
                                    OrderStatus::Processing->value,
                                    OrderStatus::ReadyForPickup->value,
                                    OrderStatus::Completed->value,
                                ]);
                        },
                    ], 'total');
            })

            ->columns([
                TextColumn::make('name')
                    ->label('Pharmacy')
                    ->description(
                        fn ($record) =>
                            $record->address ?: 'No address provided'
                    )
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('subscription.plan.name')
                    ->label('Plan')
                    ->badge()
                    ->placeholder('No plan'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) =>
                            $state instanceof PharmacyStatus
                                ? str($state->value)
                                    ->replace('_', ' ')
                                    ->title()
                                : str($state)
                                    ->replace('_', ' ')
                                    ->title()
                    )
                    ->color(
                        fn ($state) => match (
                            $state instanceof PharmacyStatus
                                ? $state->value
                                : $state
                        ) {
                            PharmacyStatus::Active->value => 'success',
                            PharmacyStatus::Suspended->value => 'danger',
                            default => 'gray',
                        }
                    )
                    ->sortable(),

                TextColumn::make('total_orders')
                    ->label('Total Orders')
                    ->numeric()
                    ->default(0)
                    ->sortable(),

                TextColumn::make('processed_orders')
                    ->label('Processed')
                    ->numeric()
                    ->default(0)
                    ->sortable(),

                TextColumn::make('revenue')
                    ->label('Revenue')
                    ->money('NGN')
                    ->default(0)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        PharmacyStatus::Active->value => 'Active',
                        PharmacyStatus::Suspended->value => 'Suspended',
                    ]),

                SelectFilter::make('plan')
                    ->label('Plan')
                    ->options(
                        SubscriptionPlan::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->query(
                        function (
                            Builder $query,
                            array $data
                        ): Builder {
                            return $query->when(
                                $data['value'] ?? null,
                                fn (Builder $query, $planId) =>
                                    $query->whereHas(
                                        'subscription',
                                        fn (Builder $query) =>
                                            $query->where(
                                                'subscription_plan_id',
                                                $planId
                                            )
                                    )
                            );
                        }
                    ),
            ])

            ->recordActions([
                ViewAction::make()
                    ->label('View Details'),
            ])

            ->defaultSort('created_at', 'desc');
    }
}