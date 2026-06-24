<?php

namespace App\Domain\Meters\Services;

use App\Domain\Meters\Models\MeterReading;
use App\Domain\Users\Models\User;

class GetMeterReadingsService
{
    public function get(
        User $user
    )
    {
        return MeterReading::with([

            'meter',

            'technician',

            'anomaly'
        ])

        ->where(

            'is_approved',

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