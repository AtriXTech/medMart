<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyPaystackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('x-paystack-signature');

        $expected = hash_hmac('sha512', $request->getContent(), config('services.paystack.secret_key'));

        if (! $signature || ! hash_equals($expected, $signature)) {
            abort(401, 'Invalid webhook signature.');
        }

        return $next($request);
    }
}
