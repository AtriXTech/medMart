<?php

declare(strict_types=1);

namespace App\Payments;

use App\Payments\Contracts\PaymentGateway;
use App\Payments\Enums\Gateway;
use App\Payments\Enums\GatewayRole;
use App\Payments\Exceptions\GatewayException;
use App\Payments\Gateways\Nomba\NombaGateway;
use App\Payments\Gateways\Paystack\PaystackGateway;

final class GatewayManager
{
    public function __construct(
        private readonly PaystackGateway $paystack,
        private readonly NombaGateway $nomba,
        private readonly GatewaySettings $settings,
    ) {
    }

    public function for(Gateway|string|null $gateway): PaymentGateway
    {
        $resolved = $gateway instanceof Gateway ? $gateway : (Gateway::fromValue($gateway) ?? Gateway::Paystack);

        return match ($resolved) {
            Gateway::Paystack => $this->paystack,
            Gateway::Nomba => $this->nomba,
        };
    }

    public function collections(): PaymentGateway
    {
        return $this->active(GatewayRole::Collections);
    }

    public function payouts(): PaymentGateway
    {
        return $this->active(GatewayRole::Payouts);
    }

    public function active(GatewayRole $role): PaymentGateway
    {
        $gateway = $this->settings->active($role);

        if (! $this->settings->isEnabled($gateway)) {
            throw GatewayException::misconfigured($gateway->label() . ' (disabled)');
        }

        $driver = $this->for($gateway);

        if (! $driver->isConfigured()) {
            throw GatewayException::misconfigured($gateway->label());
        }

        return $driver;
    }

    public function all(): array
    {
        return [Gateway::Paystack->value => $this->paystack, Gateway::Nomba->value => $this->nomba];
    }

    public function settings(): GatewaySettings
    {
        return $this->settings;
    }
}
