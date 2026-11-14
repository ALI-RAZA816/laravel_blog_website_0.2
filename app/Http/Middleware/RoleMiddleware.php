<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson() || $request->ajax()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect('/login');
        }

        if (!in_array($user->role, $roles)) {
            return $request->expectsJson() || $request->ajax()
                ? response()->json(['message' => "You don't have the access"], 403)
                : abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
