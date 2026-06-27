<?php

namespace App\Domain\Schedules\Services;

use App\Domain\Schedules\Models\Schedule;
use App\Domain\Schedules\Enums\ScheduleStatus;

class CancelScheduleService
{
    public function cancel(
        Schedule $schedule
    ): ?Schedule
    {
        if (

            $schedule->status ===
            ScheduleStatus::COMPLETED->value ||

            $schedule->status ===
            ScheduleStatus::CANCELLED->value
        ) {
            return null;
        }

        $schedule->update([

            'status' =>
                ScheduleStatus::CANCELLED->value
        ]);

        return $schedule;
    }
}