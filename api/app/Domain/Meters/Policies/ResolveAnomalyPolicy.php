<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Users\Models\User;

class ResolveAnomalyPolicy
{
    public function canResolve(
        User $user,
        MeterAnomaly $anomaly
    ): bool
    {
        if (! in_array(

            $user->role,

            [

                'staff',

                'campus_admin'
            ]
        )) {
            return false;
        }

        $meter = Meter::find(
            $anomaly->meter_id
        );

        return $meter->campus_id ===
            $user->campus_id;
    }
}