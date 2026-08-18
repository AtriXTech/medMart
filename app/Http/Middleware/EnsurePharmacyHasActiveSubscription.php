<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePharmacyHasActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $pharmacy = $request->user()->pharmacy;

        if ($pharmacy->is_test_account) {
            return $next($request);
        }

        $subscription = $pharmacy->subscription;

        if (! $subscription || $subscription->status !== SubscriptionStatus::Active) {
            abort(402, 'This pharmacy does not have an active subscription. Please renew to continue.');
        }

        return $next($request);
    }
}