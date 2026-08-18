<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRequestIsFromStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() instanceof User) {
            abort(403, 'This action is restricted to pharmacy staff.');
        }

        return $next($request);
    }
}
