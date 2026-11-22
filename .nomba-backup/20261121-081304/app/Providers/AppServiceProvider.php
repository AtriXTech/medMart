<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Customer;
use App\Services\Payments\PaymentGatewayInterface;
use App\Services\Payments\PaystackService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PaymentGatewayInterface::class,
            PaystackService::class
        );
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $path = $notifiable instanceof Customer
                ? '/customer/reset-password'
                : '/staff/reset-password';

            return config('app.frontend_url')
                . $path
                . '?token=' . $token
                . '&email=' . urlencode($notifiable->getEmailForPasswordReset());
        });
    }
}
