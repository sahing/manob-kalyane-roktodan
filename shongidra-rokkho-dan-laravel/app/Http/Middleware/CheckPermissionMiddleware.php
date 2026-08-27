<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check() || !Auth::user()->hasPermission($permission)) {
            abort(403, "Access denied. You do not possess the required permission: [{$permission}].");
        }

        return $next($request);
    }
}
