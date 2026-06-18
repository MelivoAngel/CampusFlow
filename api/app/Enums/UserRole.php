<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPERADMIN = 'superadmin';

    case CAMPUS_ADMIN = 'campus_admin';

    case STAFF = 'staff';

    case FIELD_TECHNICIAN = 'field_technician';

    case CALENDAR_ADMIN = 'calendar_admin';

    public function canCreate(self $targetRole): bool
    {
        return match ($this) {

            self::SUPERADMIN =>
                in_array($targetRole, [
                    self::CAMPUS_ADMIN
                ]),

            self::CAMPUS_ADMIN =>
                in_array($targetRole, [
                    self::STAFF,
                    self::FIELD_TECHNICIAN,
                    self::CALENDAR_ADMIN
                ]),

            self::STAFF =>
                in_array($targetRole, [
                    self::FIELD_TECHNICIAN,
                    self::CALENDAR_ADMIN
                ]),

            default => false
        };
    }
}