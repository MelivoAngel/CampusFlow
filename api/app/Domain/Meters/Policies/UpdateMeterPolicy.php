<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Meters\Models\Meter;
use App\Domain\Users\Models\User;

class UpdateMeterPolicy
{
    public function canUpdate(
        User $editor,
        Meter $meter
    ): bool
    {
        if (
            $editor->role ===
            'super_admin'
        ) {
            return true;
        }

        if (
            in_array(
                $editor->role,
                [
                    'campus_admin',
                    'staff'
                ]
            )
        ) {
            return $editor->campus_id ===
                $meter->campus_id;
        }

        return false;
    }
}