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
            return User::with(
                'campus'
            )->where(
                'role',
                '!=',
                'super_admin'
            )->get();
        }

        if (
            $user->role ===
            'campus_admin'
        ) {
            return User::with(
                'campus'
            )->where(
                'campus_id',
                $user->campus_id
            )->whereIn(
                'role',
                [
                    'staff',
                    'field_technician',
                    'calendar_admin'
                ]
            )->get();
        }

        return User::with(
            'campus'
        )->where(
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