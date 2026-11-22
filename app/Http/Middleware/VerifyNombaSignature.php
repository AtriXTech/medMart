<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Payments\Gateways\Nomba\NombaConfig;
use App\Payments\Gateways\Nomba\NombaSignature;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyNombaSignature
{
    public function __construct(private readonly NombaConfig $config)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->config->signatureKey();
        $signature = (string) ($request->header('nomba-signature') ?: $request->header('nomba-sig-value', ''));
        $timestamp = (string) $request->header('nomba-timestamp', '');
        $payload = json_decode($request->getContent(), true);

        if ($key === '' || ! is_array($payload)) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        if (! NombaSignature::matches($payload, $key, $timestamp, $signature)
            || ! NombaSignature::isFresh($timestamp, $this->config->webhookTolerance())) {
            return response()->json(['message' => 'Invalid webhook signature.'], 401);
        }

        return $next($request);
    }
}
