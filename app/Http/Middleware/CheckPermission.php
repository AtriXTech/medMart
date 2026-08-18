<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->role === 'owner' || $user->role === \App\Enums\StaffRole::Owner) {
            return $next($request);
        }

        $staffRole = $user->staffRole;

        if (!$staffRole) {
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }

        $permissions = $staffRole->permissions ?? [];

        if (!in_array($permission, $permissions)) {
            return response()->json(['message' => 'You do not have permission to perform this action.'], 403);
        }

        return $next($request);
    }
}