<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRescueTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = Auth::guard('sanctum')->user();

        // Kiểm tra nếu user là instance của ThanhVienDoi
        if (!$user instanceof \App\Models\ThanhVienDoi) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
