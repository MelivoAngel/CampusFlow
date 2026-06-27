<?php

namespace App\Domain\Facilities\Enums;

use App\Domain\Users\Enums\UserRole;

class FacilityPermission
{
    public static function canCreate(
        string $role
    ): bool
    {
        return in_array(
            $role,
            [

                UserRole::SUPER_ADMIN->value,

                UserRole::CAMPUS_ADMIN->value,

                UserRole::CALENDAR_ADMIN->value
            ]
        );
    }

    public static function requiresManualCampus(
        string $role
    ): bool
    {
        return $role ===
            UserRole::SUPER_ADMIN->value;
    }
}