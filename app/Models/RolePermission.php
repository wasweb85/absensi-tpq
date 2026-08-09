<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RolePermission extends Model
{
    protected $table = 'role_permissions';

    protected $fillable = [
        'role',
        'id_guru',
        'feature_key',
        'is_allowed',
    ];

    protected $casts = [
        'is_allowed' => 'boolean',
    ];

    /**
     * Check if a given user has permission to access a feature.
     * Superadmin (is_superadmin = 1) always returns true.
     * Checks teacher-specific override first, then falls back to role default.
     *
     * @param mixed $user
     * @param string $featureKey
     * @return bool
     */
    public static function hasAccess($user = null, string $featureKey = ''): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        // Superadmin (is_superadmin = 1) has full access to all features
        if ((int) ($user->is_superadmin ?? 0) === 1) {
            return true;
        }

        // 1. Check teacher-specific permission override first if user is linked to a teacher
        if (!empty($user->id_guru)) {
            $guruPerm = self::where('role', 'guru')
                ->where('id_guru', $user->id_guru)
                ->where('feature_key', $featureKey)
                ->first();

            if ($guruPerm) {
                return (bool) $guruPerm->is_allowed;
            }
        }

        // 2. Fall back to role-based permission
        $isSuper = (int) ($user->is_superadmin ?? 0);
        if ($isSuper === 2) {
            $role = 'kepsek';
        } elseif ($isSuper === 3 || ($isSuper === 0 && empty($user->id_guru))) {
            $role = 'admin';
        } else {
            $role = 'guru';
        }

        $perm = self::where('role', $role)
            ->whereNull('id_guru')
            ->where('feature_key', $featureKey)
            ->first();

        // If permission record exists, return its value. Otherwise, default to false.
        return $perm ? (bool) $perm->is_allowed : false;
    }
}
