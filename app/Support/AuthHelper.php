<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\NguoiDung;
use App\Models\ThanhVienDoi;

/**
 * Authentication Helper - Utilities for checking user types and roles
 */
class AuthHelper
{
    /**
     * Get the current authenticated user
     */
    public static function user()
    {
        return Auth::guard('sanctum')->user();
    }

    /**
     * Get user type (admin, nguoi-dung, thanh-vien-doi)
     */
    public static function getUserType()
    {
        $user = static::user();

        if (!$user) {
            return null;
        }

        return match (get_class($user)) {
            Admin::class => 'admin',
            NguoiDung::class => 'nguoi-dung',
            ThanhVienDoi::class => 'thanh-vien-doi',
            default => 'unknown',
        };
    }

    /**
     * Get user role based on type
     */
    public static function getUserRole()
    {
        $user = static::user();

        if (!$user) {
            return null;
        }

        if ($user instanceof Admin) {
            return $user->chucVu?->ten_chuc_vu ?? 'admin';
        }

        if ($user instanceof ThanhVienDoi) {
            return $user->vai_tro_trong_doi ?? 'member';
        }

        if ($user instanceof NguoiDung) {
            return 'user';
        }

        return 'unknown';
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(): bool
    {
        return static::user() instanceof Admin;
    }

    /**
     * Check if user is regular user (NguoiDung)
     */
    public static function isUser(): bool
    {
        return static::user() instanceof NguoiDung;
    }

    /**
     * Check if user is team member (ThanhVienDoi)
     */
    public static function isTeamMember(): bool
    {
        return static::user() instanceof ThanhVienDoi;
    }

    /**
     * Check if team member is team leader
     */
    public static function isTeamLeader(): bool
    {
        $user = static::user();

        if (!$user instanceof ThanhVienDoi) {
            return false;
        }

        return $user->vai_tro_trong_doi === 'Team Leader';
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole(string $role): bool
    {
        return static::getUserRole() === $role;
    }

    /**
     * Check if user is one of specified roles
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array(static::getUserRole(), $roles);
    }

    /**
     * Get user ID (universal method)
     */
    public static function getId()
    {
        $user = static::user();

        if (!$user) {
            return null;
        }

        return match (get_class($user)) {
            Admin::class => $user->id_admin,
            NguoiDung::class => $user->id_nguoi_dung,
            ThanhVienDoi::class => $user->id_thanh_vien_doi,
            default => null,
        };
    }

    /**
     * Get user name
     */
    public static function getName(): ?string
    {
        return static::user()?->ho_ten;
    }

    /**
     * Get user email
     */
    public static function getEmail(): ?string
    {
        return static::user()?->email;
    }

    /**
     * Get user phone
     */
    public static function getPhone(): ?string
    {
        return static::user()?->so_dien_thoai;
    }

    /**
     * Get team info (for team members)
     */
    public static function getTeamInfo()
    {
        if (!static::isTeamMember()) {
            return null;
        }

        return static::user()->doiCuuHo;
    }

    /**
     * Check if user is active
     */
    public static function isActive(): bool
    {
        $user = static::user();
        return $user?->trang_thai == 1;
    }
}
