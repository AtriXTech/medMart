<?php

declare(strict_types=1);

namespace App\Payments;

use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\Exceptions\GatewaySwitchException;
use App\Payments\Models\GatewaySetting;
use App\Settlement\Models\SettlementAuditLog;
use App\Settlement\Services\AuditLogger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

final class GatewaySettings
{
    private const CACHE_KEY = 'payments:gateway-settings';

    public function __construct(private readonly AuditLogger $audit)
    {
    }

    public function active(GatewayRole $role): Gateway
    {
        $stored = Gateway::fromValue($this->all()['active.' . $role->value] ?? null);

        if ($stored !== null) {
            return $stored;
        }

        return Gateway::fromValue((string) config('payments.' . $role->value))
            ?? Gateway::fromValue((string) config('payments.default'))
            ?? Gateway::Paystack;
    }

    public function isEnabled(Gateway $gateway): bool
    {
        return ($this->all()['enabled.' . $gateway->value] ?? true) !== false;
    }

    public function switchTo(GatewayRole $role, Gateway $gateway, ?int $actorId, string $reason, bool $configured): void
    {
        if (! $this->isEnabled($gateway)) {
            throw new GatewaySwitchException($gateway->label() . ' is disabled. Enable it before switching to it.');
        }

        if (! $configured) {
            throw new GatewaySwitchException($gateway->label() . ' is not fully configured. Add its credentials to the environment first.');
        }

        $from = $this->active($role);

        if ($from === $gateway) {
            throw new GatewaySwitchException($gateway->label() . ' is already active for this role.');
        }

        DB::transaction(function () use ($role, $gateway, $actorId, $reason, $from): void {
            $this->write('active.' . $role->value, $gateway->value, $actorId);

            $this->audit->record('gateway.switched', null, null, $actorId, [
                'role' => $role->value,
                'from' => $from->value,
                'to' => $gateway->value,
                'reason' => $reason,
            ]);
        });

        $this->flush();
    }

    public function setEnabled(Gateway $gateway, bool $enabled, ?int $actorId): void
    {
        if (! $enabled) {
            foreach (GatewayRole::cases() as $role) {
                if ($this->active($role) === $gateway) {
                    throw new GatewaySwitchException($gateway->label() . ' is active for "' . $role->label() . '". Switch that role to another gateway before disabling it.');
                }
            }
        }

        DB::transaction(function () use ($gateway, $enabled, $actorId): void {
            $this->write('enabled.' . $gateway->value, $enabled, $actorId);

            $this->audit->record($enabled ? 'gateway.enabled' : 'gateway.disabled', null, null, $actorId, ['gateway' => $gateway->value]);
        });

        $this->flush();
    }

    public function history(int $limit = 10): array
    {
        return SettlementAuditLog::query()
            ->where('action', 'like', 'gateway.%')
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->all();
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    private function all(): array
    {
        try {
            return Cache::remember(self::CACHE_KEY, (int) config('payments.cache_ttl', 300), static function (): array {
                return GatewaySetting::query()->pluck('value', 'key')->all();
            });
        } catch (Throwable $exception) {
            report($exception);

            return [];
        }
    }

    private function write(string $key, mixed $value, ?int $actorId): void
    {
        GatewaySetting::query()->updateOrCreate(['key' => $key], ['value' => $value, 'updated_by' => $actorId]);
    }
}
