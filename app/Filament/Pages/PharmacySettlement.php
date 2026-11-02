<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\PharmacyLedgerTable;
use App\Models\Pharmacy;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Services\BalanceService;
use App\Settlement\Services\SettlementAdminService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;

class PharmacySettlement extends Page
{
    public ?int $pharmacyId = null;

    protected static ?string $navigationLabel = 'Pharmacy Settlement';

    protected static ?string $title = 'Pharmacy Settlement';

    protected static string | \UnitEnum | null $navigationGroup = 'Settlement Console';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.pharmacy-settlement';

    #[Computed]
    public function pharmacyData(): ?array
    {
        if ($this->pharmacyId === null) {
            return null;
        }

        $pharmacy = Pharmacy::query()->withoutGlobalScopes()->find($this->pharmacyId);

        if ($pharmacy === null) {
            return null;
        }

        return [
            'pharmacy' => [
                'id' => $pharmacy->id,
                'name' => $pharmacy->name,
                'is_test_account' => (bool) $pharmacy->getAttribute('is_test_account'),
                'payout_hold' => (bool) $pharmacy->getAttribute('payout_hold'),
                'payout_hold_reason' => $pharmacy->getAttribute('payout_hold_reason'),
                'commission_rate' => $pharmacy->getAttribute('commission_rate'),
                'payout_schedule' => (string) ($pharmacy->getAttribute('payout_schedule') ?? 'daily'),
                'consecutive_payout_failures' => (int) $pharmacy->getAttribute('consecutive_payout_failures'),
            ],
            'balance' => app(BalanceService::class)->forPharmacy((int) $pharmacy->id),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('selectPharmacy')
                ->label(fn (): string => $this->pharmacyId === null ? 'Select pharmacy' : 'Change pharmacy')
                ->icon('heroicon-m-building-storefront')
                ->modalHeading('Select pharmacy')
                ->modalSubmitActionLabel('Load')
                ->form([
                    Select::make('pharmacy_id')
                        ->label('Pharmacy (search by name or ID)')
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(
                            fn (string $search): array => Pharmacy::query()
                                ->withoutGlobalScopes()
                                ->where(function ($query) use ($search): void {
                                    $query->where('name', 'like', '%' . $search . '%');

                                    if (ctype_digit($search)) {
                                        $query->orWhere('id', (int) $search);
                                    }
                                })
                                ->orderBy('name')
                                ->limit(50)
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->getOptionLabelUsing(
                            fn ($value): ?string => Pharmacy::query()
                                ->withoutGlobalScopes()
                                ->whereKey($value)
                                ->value('name')
                        ),
                ])
                ->action(function (array $data): void {
                    $this->pharmacyId = (int) $data['pharmacy_id'];

                    unset($this->pharmacyData);

                    $this->dispatch('pharmacy-ledger-selected', pharmacyId: $this->pharmacyId);
                }),

            Action::make('payoutSettings')
                ->label('Payout settings')
                ->icon('heroicon-m-cog-6-tooth')
                ->color('gray')
                ->visible(fn (): bool => $this->pharmacyId !== null)
                ->modalHeading('Payout settings')
                ->fillForm(function (): array {
                    $pharmacy = $this->pharmacyData['pharmacy'] ?? [];

                    return [
                        'payout_hold' => (bool) ($pharmacy['payout_hold'] ?? false),
                        'payout_hold_reason' => $pharmacy['payout_hold_reason'] ?? null,
                        'commission_rate' => $pharmacy['commission_rate'] ?? null,
                        'payout_schedule' => $pharmacy['payout_schedule'] ?? 'daily',
                    ];
                })
                ->form([
                    Checkbox::make('payout_hold')
                        ->label('Hold payouts for this pharmacy'),

                    TextInput::make('payout_hold_reason')
                        ->label('Hold reason')
                        ->maxLength(255),

                    TextInput::make('commission_rate')
                        ->label('Commission override (%)')
                        ->helperText('Leave blank to use the global rate.')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01),

                    Select::make('payout_schedule')
                        ->label('Payout schedule')
                        ->options([
                            'daily' => 'Daily',
                            'weekly' => 'Weekly',
                            'manual' => 'Manual only',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $hold = (bool) ($data['payout_hold'] ?? false);
                    $reason = trim((string) ($data['payout_hold_reason'] ?? ''));
                    $commission = $data['commission_rate'] ?? null;

                    $values = [
                        'payout_hold' => $hold,
                        'payout_hold_reason' => $hold && $reason !== '' ? $reason : null,
                        'commission_rate' => ($commission === null || $commission === '') ? null : (float) $commission,
                        'payout_schedule' => (string) $data['payout_schedule'],
                    ];

                    try {
                        app(SettlementAdminService::class)->updatePharmacyPayout(
                            (int) $this->pharmacyId,
                            $values,
                            (int) Filament::auth()->id()
                        );
                    } catch (SettlementException $exception) {
                        Notification::make()
                            ->title('Unable to save payout settings')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    unset($this->pharmacyData);

                    Notification::make()->title('Payout settings saved.')->success()->send();
                }),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PharmacyLedgerTable::class,
        ];
    }
}