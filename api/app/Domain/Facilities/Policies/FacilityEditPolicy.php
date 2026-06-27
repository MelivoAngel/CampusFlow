<?php

namespace App\Domain\Facilities\Policies;

use App\Domain\Facilities\Models\Facility;
use App\Domain\Users\Models\User;

class FacilityEditPolicy
{
    public function canEdit(
        User $user,
        Facility $facility
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
                [

                    'campus_admin',

                    'calendar_admin'
                ]
            )
        ) {
            return $user->campus_id ===
                $facility->campus_id;
        }

        return false;
    }
}