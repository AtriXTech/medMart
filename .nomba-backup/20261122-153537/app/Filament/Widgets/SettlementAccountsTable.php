<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Services\SettlementAccountService;
use Filament\Notifications\Notification;
use App\Models\SettlementAccount;
use App\Settlement\Enums\AccountStatus;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Filament\Forms\Components\Textarea;

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
                    ->description(fn(SettlementAccount $record): string => 'ID ' . $record->pharmacy_id)
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

                TextColumn::make('name_match_score')
                    ->label('Name Match')
                    ->formatStateUsing(fn($state): ?string => $state === null ? null : $state . '%')
                    ->placeholder('—')
                    ->weight('semibold')
                    ->color(fn($state): string => match (true) {
                        $state === null => 'gray',
                        (float) $state >= 80 => 'success',
                        (float) $state >= 50 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Settlement Account Status')
                    ->badge()
                    ->description(fn(SettlementAccount $record): ?string => $record->rejection_reason)
                    ->formatStateUsing(
                        fn(AccountStatus $state): string => match ($state) {
                            AccountStatus::Approved => 'Approved',
                            AccountStatus::Pending => 'Pending',
                            AccountStatus::Rejected => 'Rejected',
                            AccountStatus::Superseded => 'Superseded',
                        }
                    )
                    ->color(
                        fn(AccountStatus $state): string => match ($state) {
                            AccountStatus::Approved => 'success',
                            AccountStatus::Pending => 'warning',
                            AccountStatus::Rejected => 'danger',
                            AccountStatus::Superseded => 'gray',
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
                        'superseded' => 'Superseded',
                    ])
                    ->placeholder('All')
                    ->default('pending'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Settlement Account')
                    ->modalDescription(
                        'This creates a Paystack transfer recipient. If the pharmacy already has an approved account, payouts pause for the security cooldown.'
                    )
                    ->visible(
                        fn(SettlementAccount $record): bool =>
                        $record->status === AccountStatus::Pending
                    )
                    ->action(function (SettlementAccount $record): void {
                        try {
                            app(SettlementAccountService::class)->approve($record->id, Filament::auth()->user());
                        } catch (SettlementException | PaystackException $exception) {
                            Notification::make()
                                ->title('Unable to approve account')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()->title('Account approved.')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(
                        fn(SettlementAccount $record): bool =>
                        $record->status === AccountStatus::Pending
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Reject Settlement Account')
                    ->modalDescription(
                        'Please provide a reason for rejecting this settlement account.'
                    )
                    ->form([
                        Textarea::make('reason')
                            ->label('Rejection Reason')
                            ->placeholder(
                                'Enter the reason for rejecting this account...'
                            )
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->rows(4),
                    ])
                    ->action(function (
                        SettlementAccount $record,
                        array $data
                    ): void {
                        try {
                            app(SettlementAccountService::class)->reject($record->id, (string) $data['reason'], Filament::auth()->user());
                        } catch (SettlementException $exception) {
                            Notification::make()
                                ->title('Unable to reject account')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()->title('Account rejected.')->success()->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
