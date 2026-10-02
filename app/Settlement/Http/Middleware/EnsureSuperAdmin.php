<?php

declare(strict_types=1);

namespace App\Settlement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->is_super_admin !== true) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
