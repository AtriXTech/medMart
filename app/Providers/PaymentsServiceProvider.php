<?php

declare(strict_types=1);

namespace App\Providers;

use App\Payments\GatewayManager;
use App\Payments\GatewaySettings;
use App\Payments\Gateways\Nomba\NombaClient;
use App\Payments\Gateways\Nomba\NombaConfig;
use App\Payments\Gateways\Nomba\NombaGateway;
use App\Payments\Gateways\Nomba\NombaTokenStore;
use App\Payments\Gateways\Paystack\PaystackClient;
use App\Payments\Gateways\Paystack\PaystackGateway;
use Illuminate\Support\ServiceProvider;

final class PaymentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(NombaConfig::class);
        $this->app->singleton(NombaTokenStore::class);
        $this->app->singleton(NombaClient::class);
        $this->app->singleton(NombaGateway::class);
        $this->app->singleton(PaystackClient::class);
        $this->app->singleton(PaystackGateway::class);
        $this->app->singleton(GatewaySettings::class);
        $this->app->singleton(GatewayManager::class);
    }
}
