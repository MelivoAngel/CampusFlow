<?php

namespace App\Domain\Users\Policies;

use App\Domain\Users\Models\User;

class UserUpdatePolicy
{
    public function canUpdate(
        User $editor,
        User $target
    ): bool
    {
        if (
            $editor->id ===
            $target->id
        ) {
            return false;
        }

        if (
            $editor->role ===
            'super_admin'
        ) {
            return in_array(
                $target->role,
                [
                    'campus_admin',
                    'staff',
                    'field_technician',
                    'calendar_admin'
                ]
            );
        }

        if (
            $editor->role ===
            'campus_admin'
        ) {
            return $editor->campus_id ===
                $target->campus_id
                &&
                in_array(
                    $target->role,
                    [
                        'staff',
                        'field_technician',
                        'calendar_admin'
                    ]
                );
        }

        if (
            $editor->role ===
            'staff'
        ) {
            return $editor->campus_id ===
                $target->campus_id
                &&
                in_array(
                    $target->role,
                    [
                        'field_technician',
                        'calendar_admin'
                    ]
                );
        }

        return false;
    }
}