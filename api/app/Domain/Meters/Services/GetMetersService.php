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
        if (
            $user->role ===
            'super_admin'
        ) {
            return Meter::all();
        }

        return Meter::where(
            'campus_id',
            $user->campus_id
        )->get();
    }
}