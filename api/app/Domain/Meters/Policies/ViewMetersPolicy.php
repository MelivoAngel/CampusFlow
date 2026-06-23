<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Users\Models\User;

class ViewMetersPolicy
{
    public function canView(
        User $user
    ): bool
    {
        return in_array(
            $user->role,
            [
                'super_admin',
                'campus_admin',
                'staff'
            ]
        );
    }
}