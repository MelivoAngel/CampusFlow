<?php

namespace App\Domain\Users\Services;

use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetUsersService
{
    public function get(
        User $user
    ): Collection
    {
        if (
            $user->role ===
            'super_admin'
        ) {
            return User::all();
        }

        if (
            $user->role ===
            'campus_admin'
        ) {
            return User::where(
                'campus_id',
                $user->campus_id
            )->get();
        }

        return User::where(
            'campus_id',
            $user->campus_id
        )->whereIn(
            'role',
            [
                'field_technician',
                'calendar_admin'
            ]
        )->get();
    }
}