<?php

namespace App\Domain\Meters\Policies;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Users\Models\User;

class ReadingEditPolicy
{
    public function canEdit(
        User $user,
        MeterReading $reading
    ): bool
    {
        if (
            $user->role ===
            'field_technician'
        ) {
            return
                $reading->technician_id === $user->id &&
                ! $reading->is_approved;
        }

        if (
            in_array(
                $user->role,
                ['staff', 'campus_admin']
            )
        ) {
            $meter = Meter::find(
                $reading->meter_id
            );

            return $meter->campus_id ===
                $user->campus_id;
        }

        return false;
    }
}