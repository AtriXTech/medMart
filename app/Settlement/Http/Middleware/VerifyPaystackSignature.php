<?php

declare(strict_types=1);

namespace App\Settlement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyPaystackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = config('settlement.paystack.secret_key');
        $signature = (string) $request->header('x-paystack-signature', '');

        if (! is_string($secret) || $secret === '' || $signature === '') {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $expected = hash_hmac('sha512', $request->getContent(), $secret);

        if (! hash_equals($expected, $signature)) {
            return response()->json(['message' => 'Invalid signature.'], 401);
        }

        $allowed = (array) config('settlement.paystack.webhook_ips', []);

        if ($allowed !== [] && ! in_array((string) $request->ip(), $allowed, true)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
