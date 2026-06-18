<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPERADMIN = 'superadmin';
    case CAMPUS_ADMIN = 'campus_admin';
    case STAFF = 'staff';
    case FIELD_TECHNICIAN = 'field_technician';
    case CALENDAR_ADMIN = 'calendar_admin';
}