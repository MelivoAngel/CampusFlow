<?php

namespace App\Domain\Buildings\Policies;

use App\Domain\Buildings\Models\Building;
use App\Domain\Users\Models\User;

class BuildingEditPolicy
{
    public function canEdit(
        User $user,
        Building $building
    ): bool
    {
        if (
            $user->role ===
            'super_admin'
        ) {
            return true;
        }

        if (
            in_array(
                $user->role,
                ['campus_admin', 'staff']
            )
        ) {
            return $user->campus_id ===
                $building->campus_id;
        }

        return false;
    }
}