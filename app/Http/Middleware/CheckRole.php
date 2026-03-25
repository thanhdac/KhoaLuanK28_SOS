<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Middleware to check if user has specific role
     *
     * Usage in routes:
     *   Route::middleware(['auth:sanctum', 'check.role:admin'])->group(...)
     *   Route::middleware(['auth:sanctum', 'check.role:thanh-vien-doi,team-leader'])->group(...)
     *
     * @param Request $request
     * @param Closure $next
     * @param string $roles - Comma-separated roles required
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles = ''): mixed
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Check user type
        $userType = $this->getUserType($user);

        if (!$userType) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid user type'
            ], 403);
        }

        // If no specific roles required, just check user type
        if (empty($roles)) {
            $request->attributes->set('user_type', $userType);
            return $next($request);
        }

        // Parse required roles
        $requiredRoles = array_map('trim', explode(',', $roles));
        $userRole = $this->getUserRole($user, $userType);

        if (!in_array($userRole, $requiredRoles)) {
            return response()->json([
                'status' => false,
                'message' => "Access denied - {$userRole} role not authorized"
            ], 403);
        }

        $request->attributes->set('user_type', $userType);
        $request->attributes->set('user_role', $userRole);

        return $next($request);
    }

    /**
     * Determine user type based on model class
     */
    protected function getUserType($user): ?string
    {
        $modelClass = get_class($user);

        return match ($modelClass) {
            'App\Models\Admin' => 'admin',
            'App\Models\NguoiDung' => 'nguoi-dung',
            'App\Models\ThanhVienDoi' => 'thanh-vien-doi',
            default => null,
        };
    }

    /**
     * Get user role based on type and attributes
     */
    protected function getUserRole($user, string $userType): string
    {
        return match ($userType) {
            'admin' => $user->chucVu->ten_chuc_vu ?? 'admin',
            'thanh-vien-doi' => $user->vai_tro_trong_doi ?? 'member',
            'nguoi-dung' => 'user',
            default => 'unknown',
        };
    }
}
