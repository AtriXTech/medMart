<?php

declare(strict_types=1);

use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\Exceptions\GatewayException;
use App\Payments\Exceptions\GatewaySwitchException;
use App\Payments\GatewayManager;
use App\Payments\GatewaySettings;
use App\Settlement\Models\SettlementAuditLog;

it('defaults to the configured gateway for both roles', function () {
    $settings = app(GatewaySettings::class);

    expect($settings->active(GatewayRole::Collections))->toBe(Gateway::Paystack)
        ->and($settings->active(GatewayRole::Payouts))->toBe(Gateway::Paystack);
});

it('honours role specific defaults from the environment', function () {
    config(['payments.payouts' => 'nomba']);

    expect(app(GatewaySettings::class)->active(GatewayRole::Payouts))->toBe(Gateway::Nomba)
        ->and(app(GatewaySettings::class)->active(GatewayRole::Collections))->toBe(Gateway::Paystack);
});

it('switches each role independently and records an audit entry', function () {
    $settings = app(GatewaySettings::class);

    $settings->switchTo(GatewayRole::Collections, Gateway::Nomba, null, 'Lower fees', true);

    expect($settings->active(GatewayRole::Collections))->toBe(Gateway::Nomba)
        ->and($settings->active(GatewayRole::Payouts))->toBe(Gateway::Paystack)
        ->and(app(GatewayManager::class)->collections()->gateway())->toBe(Gateway::Nomba)
        ->and(app(GatewayManager::class)->payouts()->gateway())->toBe(Gateway::Paystack);

    $log = SettlementAuditLog::query()->where('action', 'gateway.switched')->first();

    expect($log->meta)->toMatchArray(['role' => 'collections', 'from' => 'paystack', 'to' => 'nomba', 'reason' => 'Lower fees']);
});

it('refuses to switch to an unconfigured, disabled or already active gateway', function () {
    $settings = app(GatewaySettings::class);

    expect(fn () => $settings->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'x', false))->toThrow(GatewaySwitchException::class)
        ->and(fn () => $settings->switchTo(GatewayRole::Payouts, Gateway::Paystack, null, 'x', true))->toThrow(GatewaySwitchException::class);

    $settings->setEnabled(Gateway::Nomba, false, null);

    expect(fn () => $settings->switchTo(GatewayRole::Payouts, Gateway::Nomba, null, 'x', true))->toThrow(GatewaySwitchException::class);
});

it('cannot disable a gateway that is active for a role', function () {
    expect(fn () => app(GatewaySettings::class)->setEnabled(Gateway::Paystack, false, null))->toThrow(GatewaySwitchException::class);
});

it('fails loudly when the active gateway loses its credentials', function () {
    app(GatewaySettings::class)->switchTo(GatewayRole::Collections, Gateway::Nomba, null, 'x', true);
    config(['services.nomba.client_id' => '']);

    expect(fn () => app(GatewayManager::class)->collections())->toThrow(GatewayException::class);
});

it('resolves legacy records without a gateway to Paystack', function () {
    expect(app(GatewayManager::class)->for(null)->gateway())->toBe(Gateway::Paystack)
        ->and(app(GatewayManager::class)->for('unknown')->gateway())->toBe(Gateway::Paystack);
});
