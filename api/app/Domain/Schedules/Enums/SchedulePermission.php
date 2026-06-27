<?php

namespace App\Domain\Schedules\Enums;

use App\Domain\Users\Enums\UserRole;

class SchedulePermission
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

    public static function canEdit(
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

    public static function canDelete(
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

    public static function canView(
        string $role
    ): bool
    {
        return in_array(
            $role,
            [

                UserRole::SUPER_ADMIN->value,

                UserRole::CAMPUS_ADMIN->value,

                UserRole::CALENDAR_ADMIN->value,

                UserRole::STAFF->value
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