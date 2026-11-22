<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Models\Payment;
use App\Models\Settlement;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\Exceptions\GatewayException;
use App\Payments\Exceptions\GatewaySwitchException;
use App\Payments\GatewayManager;
use App\Payments\Support\BankCodeSynchronizer;
use App\Settlement\Enums\SettlementStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Throwable;

class PaymentGateways extends Page
{
    protected static ?string $navigationLabel = 'Payment Gateways';

    protected static ?string $title = 'Payment Gateways';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.payment-gateways';

    public array $connection = [];

    public static function canAccess(): bool
    {
        return auth()->user()?->is_super_admin === true;
    }

    public function switchGatewayAction(): Action
    {
        return Action::make('switchGateway')
            ->modalHeading(fn (array $arguments): string => 'Switch ' . $this->roleFrom($arguments)->label() . ' to ' . $this->gatewayFrom($arguments)->label() . '?')
            ->modalDescription(fn (array $arguments): string => $this->switchWarning($this->roleFrom($arguments)))
            ->modalSubmitActionLabel('Switch gateway')
            ->color('warning')
            ->schema([
                Textarea::make('reason')
                    ->label('Reason for switching')
                    ->required()
                    ->maxLength(255)
                    ->rows(2),
            ])
            ->action(function (array $data, array $arguments): void {
                $role = $this->roleFrom($arguments);
                $gateway = $this->gatewayFrom($arguments);
                $manager = app(GatewayManager::class);

                try {
                    $manager->settings()->switchTo(
                        $role,
                        $gateway,
                        auth()->id(),
                        (string) $data['reason'],
                        $manager->for($gateway)->isConfigured()
                    );
                } catch (GatewaySwitchException $exception) {
                    Notification::make()->title('Gateway not switched')->body($exception->getMessage())->danger()->send();

                    return;
                }

                Notification::make()
                    ->title($role->label() . ' now use ' . $gateway->label())
                    ->success()
                    ->send();
            });
    }

    public function toggleGatewayAction(): Action
    {
        return Action::make('toggleGateway')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments): string => ($arguments['enable'] ?? false ? 'Enable ' : 'Disable ') . $this->gatewayFrom($arguments)->label() . '?')
            ->modalDescription(fn (array $arguments): string => ($arguments['enable'] ?? false)
                ? 'The gateway becomes available for selection again.'
                : 'The gateway can no longer be selected. In-flight payments and transfers already created with it still complete.')
            ->color('gray')
            ->action(function (array $arguments): void {
                $gateway = $this->gatewayFrom($arguments);

                try {
                    app(GatewayManager::class)->settings()->setEnabled($gateway, (bool) ($arguments['enable'] ?? false), auth()->id());
                } catch (GatewaySwitchException $exception) {
                    Notification::make()->title('Action not allowed')->body($exception->getMessage())->danger()->send();

                    return;
                }

                Notification::make()->title($gateway->label() . (($arguments['enable'] ?? false) ? ' enabled' : ' disabled'))->success()->send();
            });
    }

    public function syncBankCodesAction(): Action
    {
        return Action::make('syncBankCodes')
            ->requiresConfirmation()
            ->modalHeading(fn (array $arguments): string => 'Sync ' . $this->gatewayFrom($arguments)->label() . ' bank codes?')
            ->modalDescription('Banks are matched to the gateway by name. Unmatched banks keep using their default code.')
            ->color('gray')
            ->action(function (array $arguments): void {
                $gateway = app(GatewayManager::class)->for($this->gatewayFrom($arguments));

                if (! $gateway->isConfigured()) {
                    Notification::make()->title('Gateway is not configured')->danger()->send();

                    return;
                }

                try {
                    $result = app(BankCodeSynchronizer::class)->sync($gateway);
                } catch (GatewayException $exception) {
                    Notification::make()->title('Bank sync failed')->body($exception->getMessage())->danger()->send();

                    return;
                }

                $body = $result['matched'] . ' of ' . $result['total'] . ' banks matched.';

                if ($result['unmatched'] !== []) {
                    $body .= ' Unmatched: ' . implode(', ', array_slice($result['unmatched'], 0, 8)) . (count($result['unmatched']) > 8 ? '…' : '');
                }

                Notification::make()->title('Bank codes synced')->body($body)->success()->send();
            });
    }

    public function testConnection(string $gatewayName): void
    {
        $gateway = Gateway::fromValue($gatewayName);

        if ($gateway === null) {
            return;
        }

        $driver = app(GatewayManager::class)->for($gateway);

        if (! $driver->isConfigured()) {
            $this->connection[$gateway->value] = ['ok' => false, 'message' => 'Missing: ' . implode(', ', $driver->missingConfiguration())];

            return;
        }

        $started = microtime(true);

        try {
            $balance = $driver->balanceKobo();
            $this->connection[$gateway->value] = [
                'ok' => true,
                'message' => 'Connected in ' . (int) round((microtime(true) - $started) * 1000) . ' ms',
                'balance' => number_format($balance / 100, 2),
            ];
        } catch (Throwable $exception) {
            report($exception);

            $this->connection[$gateway->value] = ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    protected function getViewData(): array
    {
        $manager = app(GatewayManager::class);
        $settings = $manager->settings();

        $usage = $this->usage();
        $inFlight = $this->inFlightTransfers();
        $cards = [];

        foreach (Gateway::cases() as $gateway) {
            $driver = $manager->for($gateway);

            $cards[] = [
                'key' => $gateway->value,
                'label' => $gateway->label(),
                'configured' => $driver->isConfigured(),
                'missing' => $driver->missingConfiguration(),
                'test_mode' => $driver->isTestMode(),
                'enabled' => $settings->isEnabled($gateway),
                'roles' => array_values(array_map(
                    static fn (GatewayRole $role): string => $role->label(),
                    array_filter(GatewayRole::cases(), static fn (GatewayRole $role): bool => $settings->active($role) === $gateway)
                )),
                'usage' => $usage[$gateway->value] ?? ['paid' => 0, 'failed' => 0, 'unpaid' => 0],
                'in_flight' => $inFlight[$gateway->value] ?? 0,
                'webhooks' => $this->webhookUrls($gateway),
            ];
        }

        $roles = [];

        foreach (GatewayRole::cases() as $role) {
            $roles[] = ['key' => $role->value, 'label' => $role->label(), 'active' => $settings->active($role)->value];
        }

        return [
            'cards' => $cards,
            'roles' => $roles,
            'history' => $this->history($settings->history(10)),
        ];
    }

    private function history(array $logs): array
    {
        $names = \App\Models\User::query()
            ->whereIn('id', array_filter(array_map(static fn ($log) => $log->actor_id, $logs)))
            ->pluck('name', 'id');

        return array_map(function ($log) use ($names): array {
            $meta = (array) $log->meta;
            $from = Gateway::fromValue($meta['from'] ?? null)?->label();
            $to = Gateway::fromValue($meta['to'] ?? $meta['gateway'] ?? null)?->label();
            $role = isset($meta['role']) ? GatewayRole::tryFrom((string) $meta['role'])?->label() : null;

            return [
                'summary' => match ($log->action) {
                    'gateway.switched' => ($role ?? 'Gateway role') . ': ' . ($from ?? '—') . ' → ' . ($to ?? '—'),
                    'gateway.enabled' => ($to ?? 'Gateway') . ' enabled',
                    'gateway.disabled' => ($to ?? 'Gateway') . ' disabled',
                    default => (string) $log->action,
                },
                'reason' => $meta['reason'] ?? null,
                'actor' => $log->actor_id !== null ? ($names[$log->actor_id] ?? 'User #' . $log->actor_id) : 'System',
                'at' => $log->created_at?->format('M j, Y g:i A'),
            ];
        }, $logs);
    }

    private function usage(): array
    {
        $rows = DB::table('payments')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('gateway, status, count(*) as total')
            ->groupBy('gateway', 'status')
            ->get();

        $usage = [];

        foreach ($rows as $row) {
            $bucket = match ((string) $row->status) {
                'paid', 'refunded' => 'paid',
                'failed' => 'failed',
                default => 'unpaid',
            };

            $usage[(string) $row->gateway][$bucket] = ($usage[(string) $row->gateway][$bucket] ?? 0) + (int) $row->total;
        }

        return $usage;
    }

    private function inFlightTransfers(): array
    {
        return Settlement::query()
            ->withoutGlobalScopes()
            ->where('status', SettlementStatus::Processing->value)
            ->selectRaw('coalesce(gateway, ?) as gateway, count(*) as total', [Gateway::Paystack->value])
            ->groupByRaw('coalesce(gateway, ?)', [Gateway::Paystack->value])
            ->pluck('total', 'gateway')
            ->map(static fn (mixed $total): int => (int) $total)
            ->all();
    }

    private function webhookUrls(Gateway $gateway): array
    {
        return match ($gateway) {
            Gateway::Paystack => [
                'Payments' => url('/api/v1/webhooks/paystack'),
                'Transfers' => url('/api/v1/webhooks/paystack/transfers'),
            ],
            Gateway::Nomba => [
                'Payments and transfers' => url('/api/v1/webhooks/nomba'),
            ],
        };
    }

    private function switchWarning(GatewayRole $role): string
    {
        return $role === GatewayRole::Collections
            ? 'New checkouts and subscription payments will use the new gateway. Payments already started keep verifying and refunding on the gateway that created them.'
            : 'New payouts will use the new gateway and its balance. Transfers already submitted keep reconciling on the gateway that sent them, so make sure the new gateway balance is funded.';
    }

    private function roleFrom(array $arguments): GatewayRole
    {
        return GatewayRole::from((string) ($arguments['role'] ?? GatewayRole::Collections->value));
    }

    private function gatewayFrom(array $arguments): Gateway
    {
        return Gateway::from((string) ($arguments['gateway'] ?? Gateway::Paystack->value));
    }
}
