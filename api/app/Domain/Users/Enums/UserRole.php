<?php

namespace App\Domain\Users\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';

    case CAMPUS_ADMIN = 'campus_admin';

    case STAFF = 'staff';

    case FIELD_TECHNICIAN = 'field_technician';

    case CALENDAR_ADMIN = 'calendar_admin';

    public function canCreate(string $role): bool
    {
        return match ($this) {

            self::SUPER_ADMIN =>
                in_array(
                    $role,
                    [
                        self::CAMPUS_ADMIN->value,
                        self::STAFF->value,
                        self::FIELD_TECHNICIAN->value,
                        self::CALENDAR_ADMIN->value
                    ]
                ),

            self::CAMPUS_ADMIN =>
                in_array(
                    $role,
                    [
                        self::STAFF->value,
                        self::FIELD_TECHNICIAN->value,
                        self::CALENDAR_ADMIN->value
                    ]
                ),

            self::STAFF =>
                in_array(
                    $role,
                    [
                        self::FIELD_TECHNICIAN->value,
                        self::CALENDAR_ADMIN->value
                    ]
                ),

            default => false
        };
    }

    public function requiresManualCampus(): bool
    {
        return match ($this) {

            self::SUPER_ADMIN => true,

            default => false
        };
    }
}