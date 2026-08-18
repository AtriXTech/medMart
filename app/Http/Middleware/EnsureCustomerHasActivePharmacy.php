<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\SubscriptionStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerHasActivePharmacy
{
    public function handle(Request $request, Closure $next): Response
    {
        $customer = $request->user();

        $activeLink = $customer->pharmacyLinks()->where('is_active', true)->with('pharmacy')->first();

        if (! $activeLink) {
            abort(422, 'You need to join a pharmacy before browsing products.');
        }

        if ($activeLink->is_suspended) {
            abort(403, 'Your access to this pharmacy has been suspended. Please contact the pharmacy or switch to another linked pharmacy.');
        }

        $pharmacy = $activeLink->pharmacy;

        if (! $pharmacy->is_test_account) {
            $subscription = $pharmacy->subscription;

            if (! $subscription || $subscription->status !== SubscriptionStatus::Active) {
                abort(503, 'This pharmacy is temporarily unavailable. Please try again later or switch to another linked pharmacy.');
            }
        }

        return $next($request);
    }
}
