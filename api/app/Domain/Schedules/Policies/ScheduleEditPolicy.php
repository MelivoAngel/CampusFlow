<?php

namespace App\Domain\Schedules\Policies;

use App\Domain\Schedules\Models\Schedule;
use App\Domain\Users\Models\User;

class ScheduleEditPolicy
{
    public function canEdit(
        User $user,
        Schedule $schedule
    ): bool
    {
        if (
            $user->role ===
            'super_admin'
        ) {
            return true;
        }

        if (
            in_array(
                $user->role,
                [

                    'campus_admin',

                    'calendar_admin'
                ]
            )
        ) {
            return $user->campus_id ===
                $schedule->campus_id;
        }

        return false;
    }
}