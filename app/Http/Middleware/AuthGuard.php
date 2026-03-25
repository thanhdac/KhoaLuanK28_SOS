<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthGuard
{
    /**
     * Handle incoming request with specific guard authentication
     *
     * Usage:
     *   Route::middleware(['auth.guard:admin'])->group(function () { ... })
     *   Route::middleware(['auth.guard:nguoi-dung'])->group(function () { ... })
     *   Route::middleware(['auth.guard:thanh-vien-doi'])->group(function () { ... })
     */
    public function handle(Request $request, Closure $next, $guard = 'sanctum'): mixed
    {
        if (!Auth::guard($guard)->check()) {
            return response()->json([
                'status' => false,
                'message' => "Unauthorized - {$guard} authentication required"
            ], 401);
        }

        $request->attributes->set('guard', $guard);
        $request->attributes->set('user', Auth::guard($guard)->user());

        return $next($request);
    }
}
