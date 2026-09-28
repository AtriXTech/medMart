<?php

namespace App\Filament\Resources\Pharmacies\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CustomerLinksRelationManager extends RelationManager
{
    protected static string $relationship = 'customerLinks';

    protected static ?string $title = 'Customers';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.username')
                    ->label('Username')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('customer.email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('customer.phone')
                    ->label('Phone'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (bool $state): string => $state ? 'Active' : 'Inactive'
                    )
                    ->color(
                        fn (bool $state): string => $state ? 'success' : 'gray'
                    ),

                TextColumn::make('is_suspended')
                    ->label('Suspension')
                    ->badge()
                    ->formatStateUsing(
                        fn (bool $state): string => $state ? 'Suspended' : 'Normal'
                    )
                    ->color(
                        fn (bool $state): string => $state ? 'danger' : 'success'
                    ),

                TextColumn::make('created_at')
                    ->label('Linked')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status'),

                TernaryFilter::make('is_suspended')
                    ->label('Suspension'),
            ]);
    }
}