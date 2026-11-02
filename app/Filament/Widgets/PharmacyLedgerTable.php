<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Settlement\Enums\LedgerEntryType;
use App\Settlement\Models\LedgerEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class PharmacyLedgerTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public ?int $pharmacyId = null;

    #[On('pharmacy-ledger-selected')]
    public function setPharmacy(?int $pharmacyId = null): void
    {
        $this->pharmacyId = $pharmacyId;

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Ledger')
            ->query(
                fn (): Builder => LedgerEntry::query()
                    ->where('pharmacy_id', $this->pharmacyId ?? 0)
            )
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y · H:i')
                    ->timezone('Africa/Lagos')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(
                        fn ($state): string => $state instanceof \BackedEnum ? $state->value : (string) $state
                    ),

                TextColumn::make('payment_id')
                    ->label('Payment')
                    ->formatStateUsing(fn ($state): string => '#' . $state)
                    ->placeholder('—'),

                TextColumn::make('settlement_id')
                    ->label('Settlement')
                    ->formatStateUsing(fn ($state): string => '#' . $state)
                    ->placeholder('—'),

                TextColumn::make('amount_kobo')
                    ->label('Amount')
                    ->alignEnd()
                    ->weight('semibold')
                    ->color(fn ($state): string => (int) $state >= 0 ? 'success' : 'danger')
                    ->formatStateUsing(
                        fn ($state): string => ((int) $state >= 0 ? '+' : '-')
                            . '₦' . number_format(abs((int) $state) / 100, 2)
                    ),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type')
                    ->options(
                        collect(LedgerEntryType::cases())
                            ->mapWithKeys(fn (LedgerEntryType $case): array => [$case->value => $case->value])
                            ->all()
                    ),
            ])
            ->emptyStateHeading(
                fn (): string => $this->pharmacyId === null
                    ? 'Select a pharmacy to see its ledger'
                    : 'No ledger entries.'
            )
            ->defaultSort('id', 'desc');
    }
}