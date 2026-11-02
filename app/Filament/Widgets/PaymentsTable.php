<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Payment;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Services\PaymentDisputeService;
use App\Settlement\Services\PaymentRefundService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PaymentsTable extends TableWidget
{
    protected static bool $isDiscovered = false;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->withoutGlobalScopes()
                    ->with('pharmacy:id,name')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('Payment')
                    ->formatStateUsing(fn ($state): string => '#' . $state)
                    ->description(fn (Payment $record): string => 'Order #' . $record->order_id)
                    ->sortable(),

                TextColumn::make('reference')
                    ->label('Reference')
                    ->searchable(),

                TextColumn::make('pharmacy.name')
                    ->label('Pharmacy')
                    ->description(fn (Payment $record): string => 'ID ' . $record->pharmacy_id)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gross_kobo')
                    ->label('Gross')
                    ->formatStateUsing(fn ($state): string => (int) $state > 0 ? $this->naira($state) : '—')
                    ->sortable(),

                TextColumn::make('fees')
                    ->label('Fees')
                    ->state(
                        fn (Payment $record): string => (int) $record->gross_kobo > 0
                            ? $this->naira((int) $record->gross_kobo - (int) $record->net_kobo)
                            : '—'
                    ),

                TextColumn::make('net_kobo')
                    ->label('Net')
                    ->weight('semibold')
                    ->formatStateUsing(
                        fn ($state, Payment $record): string => (int) $record->gross_kobo > 0
                            ? $this->naira($state)
                            : '—'
                    )
                    ->sortable(),

                TextColumn::make('settlement_status')
                    ->label('Status')
                    ->badge()
                    ->state(fn (Payment $record): string => $this->displayStatus($record))
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'unrecorded' => 'Unrecorded',
                            'not_paid' => 'Not paid',
                            default => ucfirst($state),
                        }
                    )
                    ->color(
                        fn (string $state): string => match ($state) {
                            'settled' => 'success',
                            'pending', 'unrecorded' => 'warning',
                            'eligible' => 'info',
                            'held' => 'danger',
                            default => 'gray',
                        }
                    )
                    ->description(fn (Payment $record): ?string => $record->getAttribute('hold_reason')),

                TextColumn::make('eligible_at')
                    ->label('Eligible at')
                    ->dateTime('M j, Y · H:i')
                    ->timezone('Africa/Lagos')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('settlement_status')
                    ->label('Status')
                    ->options([
                        'unrecorded' => 'Paid, not recorded',
                        'pending' => 'Pending (hold window)',
                        'eligible' => 'Eligible',
                        'settled' => 'Settled',
                        'held' => 'Held',
                        'refunded' => 'Refunded',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (blank($value)) {
                            return $query;
                        }

                        if ($value === 'unrecorded') {
                            return $query
                                ->whereNull('settlement_status')
                                ->whereIn('status', (array) config('settlement.payment.paid_statuses'));
                        }

                        return $query->where('settlement_status', $value);
                    }),

                SelectFilter::make('pharmacy_id')
                    ->label('Pharmacy')
                    ->relationship('pharmacy', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('order')
                    ->form([
                        TextInput::make('order_id')
                            ->label('Order ID')
                            ->numeric()
                            ->minValue(1),
                    ])
                    ->query(
                        fn (Builder $query, array $data): Builder => $query->when(
                            $data['order_id'] ?? null,
                            fn (Builder $query, $orderId): Builder => $query->where('order_id', (int) $orderId)
                        )
                    ),
            ])
            ->recordActions([
                Action::make('hold')
                    ->label('Hold')
                    ->icon('heroicon-m-pause')
                    ->color('gray')
                    ->modalHeading('Hold payment')
                    ->modalDescription('The payment will not be settled until released.')
                    ->visible(
                        fn (Payment $record): bool => in_array($this->settlementStatus($record), ['pending', 'eligible'], true)
                    )
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->rows(3),
                    ])
                    ->action(function (Payment $record, array $data): void {
                        $this->perform(
                            fn () => app(PaymentDisputeService::class)->hold($record->id, (string) $data['reason'], $this->actorId()),
                            'Payment held.',
                            'Unable to hold payment'
                        );
                    }),

                Action::make('release')
                    ->label('Release')
                    ->icon('heroicon-m-play')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Release payment')
                    ->modalDescription('The payment returns to the normal settlement flow.')
                    ->visible(
                        fn (Payment $record): bool => $this->settlementStatus($record) === 'held'
                    )
                    ->action(function (Payment $record): void {
                        $this->perform(
                            fn () => app(PaymentDisputeService::class)->release($record->id, $this->actorId()),
                            'Payment released.',
                            'Unable to release payment'
                        );
                    }),

                Action::make('refund')
                    ->label('Refund')
                    ->icon('heroicon-m-arrow-uturn-left')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Refund payment')
                    ->modalDescription(
                        'The full amount is refunded to the customer and reversed from the pharmacy ledger. If it was already paid out, the amount is deducted from the next payout.'
                    )
                    ->visible(
                        fn (Payment $record): bool => in_array($this->settlementStatus($record), ['pending', 'eligible', 'held', 'settled'], true)
                    )
                    ->form([
                        Textarea::make('reason')
                            ->label('Reason')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->rows(3),

                        Checkbox::make('via_gateway')
                            ->label('Also refund through Paystack')
                            ->default(true),
                    ])
                    ->action(function (Payment $record, array $data): void {
                        $this->perform(
                            fn () => app(PaymentRefundService::class)->refund(
                                $record->id,
                                (string) $data['reason'],
                                $this->actorId(),
                                (bool) ($data['via_gateway'] ?? true)
                            ),
                            'Payment refunded.',
                            'Unable to refund payment'
                        );
                    }),
            ])
            ->defaultSort('id', 'desc');
    }

    private function perform(callable $callback, string $success, string $failureTitle): void
    {
        try {
            $callback();
        } catch (SettlementException | PaystackException $exception) {
            Notification::make()
                ->title($failureTitle)
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()->title($success)->success()->send();
    }

    private function actorId(): ?int
    {
        $id = Filament::auth()->id();

        return $id === null ? null : (int) $id;
    }

    private function naira(int | string | null $kobo): string
    {
        return '₦' . number_format(((int) $kobo) / 100, 2);
    }

    private function settlementStatus(Payment $record): ?string
    {
        $status = $record->getAttribute('settlement_status');

        if ($status instanceof \BackedEnum) {
            $status = $status->value;
        }

        return $status === null || $status === '' ? null : (string) $status;
    }

    private function displayStatus(Payment $record): string
    {
        $status = $this->settlementStatus($record);

        if ($status !== null) {
            return $status;
        }

        $payment = $record->status instanceof \BackedEnum
            ? $record->status->value
            : (string) $record->status;

        return in_array(strtolower($payment), (array) config('settlement.payment.paid_statuses'), true)
            ? 'unrecorded'
            : 'not_paid';
    }
}