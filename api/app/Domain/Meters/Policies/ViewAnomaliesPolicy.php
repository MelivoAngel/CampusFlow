<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Users\Models\User;

class ViewAnomaliesPolicy
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