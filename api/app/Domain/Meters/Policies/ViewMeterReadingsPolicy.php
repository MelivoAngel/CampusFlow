<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Users\Models\User;

class ViewMeterReadingsPolicy
{
    public function canView(
        User $user
    ): bool
    {
        return in_array(

            $user->role,

            [

                'staff',

                'campus_admin'
            ]
        );
    }
}