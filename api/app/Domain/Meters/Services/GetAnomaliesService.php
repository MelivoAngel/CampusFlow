<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Users\Models\User;

class GetAnomaliesService
{
    public function get(
        User $user
    )
    {
        return MeterAnomaly::with([

            'meter',

            'reading'

        ])

        ->where(

            'is_resolved',

            false
        )

        ->whereHas(

            'meter',

            function ($query) use ($user) {

                $query->where(

                    'campus_id',

                    $user->campus_id
                );
            }
        )

        ->latest()

        ->get();
    }
}