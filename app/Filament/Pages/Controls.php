<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Pharmacy;
use App\Settlement\Exceptions\PaystackException;
use App\Settlement\Exceptions\SettlementException;
use App\Settlement\Gateway\PaystackClient;
use App\Settlement\Services\AuditLogger;
use App\Settlement\Services\SettingsService;
use App\Settlement\Services\SettlementAdminService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Computed;
use Throwable;

class Controls extends Page
{
    public ?array $gateway = null;

    public string $jobOutput = '';

    protected static ?string $navigationLabel = 'Controls';

    protected static ?string $title = 'Controls';

    protected static string | \UnitEnum | null $navigationGroup = 'Settlement Console';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.controls';

    public function mount(): void
    {
        $this->loadGateway();
    }

    #[Computed]
    public function controls(): array
    {
        return app(SettingsService::class)->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pausePayouts')
                ->label('Pause all payouts')
                ->icon('heroicon-m-pause-circle')
                ->color('danger')
                ->visible(fn (): bool => ! ($this->controls['payouts_paused'] ?? false))
                ->modalHeading('Pause all payouts')
                ->modalDescription(
                    'No new settlements will be created and in-flight ones that have not been sent will be held.'
                )
                ->modalSubmitActionLabel('Pause payouts')
                ->form([
                    Textarea::make('reason')
                        ->label('Reason')
                        ->required()
                        ->minLength(3)
                        ->maxLength(255)
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    app(SettlementAdminService::class)->pausePayouts((string) $data['reason'], $this->actorId());

                    unset($this->controls);

                    Notification::make()->title('Payouts paused.')->success()->send();
                }),

            Action::make('resumePayouts')
                ->label('Resume payouts')
                ->icon('heroicon-m-play-circle')
                ->color('success')
                ->visible(fn (): bool => (bool) ($this->controls['payouts_paused'] ?? false))
                ->requiresConfirmation()
                ->modalHeading('Resume payouts')
                ->modalDescription('Settlements held because of the pause will be released and sent.')
                ->modalSubmitActionLabel('Resume payouts')
                ->action(function (): void {
                    $released = app(SettlementAdminService::class)->resumePayouts($this->actorId());

                    unset($this->controls);

                    Notification::make()
                        ->title('Payouts resumed.')
                        ->body('Released settlements: ' . $released . '.')
                        ->success()
                        ->send();
                }),

            Action::make('commissionSettings')
                ->label('Commission and fees')
                ->icon('heroicon-m-receipt-percent')
                ->color('gray')
                ->modalHeading('Commission and fees')
                ->modalDescription('Changes apply to payments recorded from now on.')
                ->fillForm(function (): array {
                    $controls = $this->controls;

                    return [
                        'commission_enabled' => (bool) $controls['commission_enabled'],
                        'commission_rate' => $controls['commission_rate'],
                        'commission_flat_kobo' => $controls['commission_flat_kobo'],
                        'commission_cap_kobo' => $controls['commission_cap_kobo'],
                        'gateway_fee_bearer' => $controls['gateway_fee_bearer'],
                    ];
                })
                ->form([
                    Checkbox::make('commission_enabled')
                        ->label('Take commission on sales'),

                    TextInput::make('commission_rate')
                        ->label('Rate (%)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->maxValue(100)
                        ->step(0.01),

                    TextInput::make('commission_flat_kobo')
                        ->label('Flat (kobo)')
                        ->helperText('100 kobo = ₦1.')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->step(1),

                    TextInput::make('commission_cap_kobo')
                        ->label('Cap (kobo)')
                        ->helperText('Cap 0 means no cap.')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->step(1),

                    Select::make('gateway_fee_bearer')
                        ->label('Paystack fee is paid by')
                        ->options([
                            'pharmacy' => 'Pharmacy (deducted from payout)',
                            'platform' => 'Platform (absorbed)',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $values = [
                        'commission_enabled' => (bool) ($data['commission_enabled'] ?? false),
                        'commission_rate' => (float) $data['commission_rate'],
                        'commission_flat_kobo' => (int) $data['commission_flat_kobo'],
                        'commission_cap_kobo' => (int) $data['commission_cap_kobo'],
                        'gateway_fee_bearer' => (string) $data['gateway_fee_bearer'],
                    ];

                    $actorId = $this->actorId();

                    app(SettingsService::class)->update($values, $actorId);
                    app(AuditLogger::class)->record('settlement.settings_updated', null, null, $actorId, $values);

                    unset($this->controls);

                    Notification::make()->title('Settings saved.')->success()->send();
                }),

            Action::make('runBatch')
                ->label('Run settlement batch')
                ->icon('heroicon-m-bolt')
                ->modalHeading('Run settlement batch')
                ->modalDescription(
                    'Creates settlements from eligible payments and dispatches transfers. With a pharmacy ID, the pharmacy schedule is ignored.'
                )
                ->modalSubmitActionLabel('Run now')
                ->form([
                    TextInput::make('pharmacy_id')
                        ->label('Pharmacy ID (optional)')
                        ->helperText('Leave blank to run for all pharmacies.')
                        ->numeric()
                        ->minValue(1),
                ])
                ->action(function (array $data): void {
                    $pharmacyId = filled($data['pharmacy_id'] ?? null) ? (int) $data['pharmacy_id'] : null;

                    if ($pharmacyId !== null
                        && ! Pharmacy::query()->withoutGlobalScopes()->whereKey($pharmacyId)->exists()
                    ) {
                        Notification::make()->title('Pharmacy not found.')->danger()->send();

                        return;
                    }

                    try {
                        $result = app(SettlementAdminService::class)->runBatch($pharmacyId, $this->actorId());
                    } catch (SettlementException | PaystackException $exception) {
                        Notification::make()
                            ->title('Unable to run batch')
                            ->body($exception->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($pharmacyId !== null) {
                        if ((int) ($result['created'] ?? 0) > 0) {
                            Notification::make()
                                ->title('Settlement created: ' . $result['reference'])
                                ->success()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('Nothing to settle for this pharmacy.')
                            ->body((string) ($result['reason'] ?? ''))
                            ->warning()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title(
                            'Created: ' . ($result['created'] ?? 0)
                            . ', skipped: ' . ($result['skipped'] ?? 0)
                            . ', failed: ' . ($result['failed'] ?? 0)
                            . (! empty($result['paused']) ? ' (payouts paused)' : '')
                        )
                        ->success()
                        ->send();
                }),

            ActionGroup::make([
                Action::make('jobRecordPayments')
                    ->label('Record missed payments')
                    ->modalHeading('Record missed payments')
                    ->modalDescription('Records paid payments that have not been recorded for settlement yet.')
                    ->form([
                        DatePicker::make('since')
                            ->label('Record payments since')
                            ->helperText('Optional. Leave blank to use the default look-back window.'),
                    ])
                    ->action(function (array $data): void {
                        $arguments = ['--sync' => true];

                        if (filled($data['since'] ?? null)) {
                            $arguments['--since'] = (string) $data['since'];
                        }

                        $this->runJob('record-payments', 'settlement:record-payments', $arguments);
                    }),

                Action::make('jobPromoteEligible')
                    ->label('Promote eligible')
                    ->action(fn () => $this->runJob('promote-eligible', 'settlement:promote-eligible')),

                Action::make('jobReconcileTransfers')
                    ->label('Reconcile transfers')
                    ->action(fn () => $this->runJob('reconcile-transfers', 'settlement:reconcile-transfers')),

                Action::make('jobReconcileLedger')
                    ->label('Reconcile ledger')
                    ->action(fn () => $this->runJob('reconcile-ledger', 'settlement:reconcile-ledger')),
            ])
                ->label('Maintenance jobs')
                ->icon('heroicon-m-wrench-screwdriver')
                ->color('gray')
                ->button(),

            Action::make('refreshGateway')
                ->label('Refresh Paystack balance')
                ->icon('heroicon-m-arrow-path')
                ->color('gray')
                ->action(function (): void {
                    $this->loadGateway();

                    Notification::make()->title('Paystack balance refreshed.')->success()->send();
                }),
        ];
    }

    private function runJob(string $job, string $command, array $arguments = []): void
    {
        try {
            $exitCode = Artisan::call($command, $arguments);
            $output = trim(Artisan::output());
        } catch (Throwable $exception) {
            $this->jobOutput = '[' . $job . '] failed' . "\n" . $exception->getMessage();

            Notification::make()
                ->title('Job failed.')
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $this->jobOutput = '[' . $job . '] exit code ' . $exitCode . "\n" . ($output !== '' ? $output : '(no output)');

        $notification = Notification::make()->title('Job finished.');

        $exitCode === 0 ? $notification->success()->send() : $notification->danger()->send();
    }

    private function loadGateway(): void
    {
        $client = app(PaystackClient::class);
        $balance = null;
        $error = null;

        try {
            $balance = $client->balance();
        } catch (PaystackException $exception) {
            $error = $exception->getMessage();
        }

        $this->gateway = [
            'mode' => $client->isTestKey() ? 'test' : 'live',
            'balance_kobo' => $balance,
            'error' => $error,
        ];
    }

    private function actorId(): int
    {
        return (int) Filament::auth()->id();
    }
}