<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\Meter;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;

class GetMetersService
{
    public function get(
        User $user
    ): Collection
    {
        $query = Meter::with([

            'assignment.technician:id,name'
        ]);

        if (
            $user->role ===
            'super_admin'
        ) {
            return $query->get();
        }

        return $query->where(

            'campus_id',

            $user->campus_id

        )->get();
    }
}