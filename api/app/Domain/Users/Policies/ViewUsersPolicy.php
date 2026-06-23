<?php

namespace App\Domain\Users\Policies;

use App\Domain\Users\Models\User;

class ViewUsersPolicy
{
    public function canView(
        User $user
    ): bool
    {
        return in_array(
            $user->role,
            ['super_admin', 'campus_admin', 'staff']
        );
    }
}