<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\SettlementAccount;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class SettlementAccountsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SettlementAccount::withoutGlobalScopes()
                    ->with('pharmacy')
            )
            ->columns([
                TextColumn::make('pharmacy.name')
                    ->label('Pharmacy')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable(),

                TextColumn::make('account_name')
                    ->label('Account Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Settlement Account Status')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'approved' => 'Approved',
                            'pending' => 'Pending',
                            'rejected' => 'Rejected',
                            default => ucfirst($state),
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'approved' => 'success',
                            'pending' => 'warning',
                            'rejected' => 'danger',
                            default => 'gray',
                        }
                    ),

                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M j, Y · H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Settlement Account Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
->recordActions([
    Action::make('approve')
        ->label('Approve')
        ->icon('heroicon-m-check')
        ->color('success')
        ->requiresConfirmation()
        ->modalHeading('Approve Settlement Account')
        ->modalDescription(
            'Are you sure you want to approve this settlement account?'
        )
        ->visible(
            fn (SettlementAccount $record): bool =>
                $record->status === 'pending'
        )
        ->action(function (SettlementAccount $record): void {
            $record->update([
                'status' => 'approved',
                'reviewed_by_id' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => null,
            ]);
        }),

    Action::make('reject')
        ->label('Reject')
        ->icon('heroicon-m-x-mark')
        ->color('danger')
        ->visible(
            fn (SettlementAccount $record): bool =>
                $record->status === 'pending'
        )
        ->modalHeading('Reject Settlement Account')
        ->modalDescription(
            'Please provide a reason for rejecting this settlement account.'
        )
        ->form([
            \Filament\Forms\Components\Textarea::make('reason')
                ->label('Rejection Reason')
                ->placeholder('Enter the reason for rejecting this account...')
                ->required()
                ->maxLength(255)
                ->rows(4),
        ])
        ->action(function (SettlementAccount $record, array $data): void {
            $record->update([
                'status' => 'rejected',
                'reviewed_by_id' => auth()->id(),
                'reviewed_at' => now(),
                'rejection_reason' => $data['reason'],
            ]);
        }),
])
            ->defaultSort('created_at', 'desc');
    }
}