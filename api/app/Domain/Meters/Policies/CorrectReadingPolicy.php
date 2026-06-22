<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Users\Models\User;

class CorrectReadingPolicy
{
    public function canCorrect(
        User $user,
        MeterReading $reading
    ): bool
    {
        if (
            ! in_array(
                $user->role,
                ['staff', 'campus_admin']
            )
        ) {
            return false;
        }

        $meter = Meter::find(
            $reading->meter_id
        );

        return $meter->campus_id ===
            $user->campus_id;
    }
}