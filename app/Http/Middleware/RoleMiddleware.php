<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!$request->user()) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    foreach ($roles as $role) {
        if ($request->user()->roles->contains('name', $role)) {
            return $next($request);
        }
    }

    return response()->json(['message' => 'Forbidden'], 403);
}
}
