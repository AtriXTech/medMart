<?php

namespace App\Filament\Resources\Pharmacies\Schemas;

use App\Enums\PharmacyStatus;
use App\Enums\SubscriptionStatus;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PharmacyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Pharmacy Overview')
                    ->description('Basic information about this pharmacy.')
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Pharmacy Name')
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (PharmacyStatus $state): string => match ($state) {
                                    PharmacyStatus::Active => 'Active',
                                    PharmacyStatus::Suspended => 'Suspended',
                                }
                            )
                            ->color(
                                fn (PharmacyStatus $state): string => match ($state) {
                                    PharmacyStatus::Active => 'success',
                                    PharmacyStatus::Suspended => 'danger',
                                }
                            ),

                        TextEntry::make('is_test_account')
                            ->label('Account Type')
                            ->badge()
                            ->formatStateUsing(
                                fn (bool $state): string => $state ? 'Test Account' : 'Live Account'
                            )
                            ->color(
                                fn (bool $state): string => $state ? 'warning' : 'success'
                            ),

                        TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),

                        TextEntry::make('phone')
                            ->label('Phone')
                            ->placeholder('Not provided'),

                        TextEntry::make('address')
                            ->label('Address')
                            ->placeholder('Not provided')
                            ->columnSpanFull(),

                        TextEntry::make('timezone')
                            ->label('Timezone'),

                        TextEntry::make('currency')
                            ->label('Currency'),

                        TextEntry::make('created_at')
                            ->label('Joined')
                            ->dateTime('M d, Y'),

                    ])
                    ->columns(2),

                Section::make('Subscription')
                    ->description('Current subscription information.')
                    ->icon('heroicon-o-credit-card')
                    ->schema([
                        TextEntry::make('subscription.plan.name')
                            ->label('Plan')
                            ->placeholder('No subscription'),

                        TextEntry::make('subscription.status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(
                                fn (?SubscriptionStatus $state): string => match ($state) {
                                    SubscriptionStatus::Active => 'Active',
                                    SubscriptionStatus::Inactive => 'Inactive',
                                    SubscriptionStatus::Cancelled => 'Cancelled',
                                    SubscriptionStatus::PastDue => 'Past Due',
                                    null => 'No Subscription',
                                }
                            )
                            ->color(
                                fn (?SubscriptionStatus $state): string => match ($state) {
                                    SubscriptionStatus::Active => 'success',
                                    SubscriptionStatus::Inactive => 'gray',
                                    SubscriptionStatus::Cancelled => 'danger',
                                    SubscriptionStatus::PastDue => 'warning',
                                    null => 'gray',
                                }
                            ),

                        TextEntry::make('subscription.current_period_starts_at')
                            ->label('Current Period Started')
                            ->dateTime('M d, Y')
                            ->placeholder('—'),

                        TextEntry::make('subscription.current_period_ends_at')
                            ->label('Current Period Ends')
                            ->dateTime('M d, Y')
                            ->placeholder('—'),

                        TextEntry::make('subscription.cancelled_at')
                            ->label('Cancelled At')
                            ->dateTime('M d, Y')
                            ->placeholder('Not cancelled'),

                    ])
                    ->columns(2),
            ]);
    }
}