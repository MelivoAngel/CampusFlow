<?php

namespace App\Domain\Meters\Enums;

use App\Domain\Users\Enums\UserRole;

class MeterPermission
{
    public static function canCreate(
        string $role
    ): bool
    {
        return in_array(
            $role,
            [
                UserRole::SUPERADMIN->value,
                UserRole::CAMPUS_ADMIN->value,
                UserRole::STAFF->value
            ]
        );
    }

    public static function requiresManualCampus(
        string $role
    ): bool
    {
        return $role ===
            UserRole::SUPERADMIN->value;
    }
}