<?php

namespace App\Domain\Schedules\Services;

use App\Domain\Schedules\Models\Schedule;
use App\Domain\Schedules\Enums\ScheduleStatus;

class RescheduleScheduleService
{
    public function reschedule(
        Schedule $schedule,
        array $data
    ): ?Schedule
    {
        $overlap = Schedule::where(

            'id',

            '!=',

            $schedule->id

        )->where(

            'facility_id',

            $data['facility_id']

        )->where(

            'event_date',

            $data['event_date']

        )->where(function (
            $query
        ) use ($data) {

            $query->where(

                'start_time',

                '<',

                $data['end_time']

            )->where(

                'end_time',

                '>',

                $data['start_time']
            );

        })->exists();

        if (
            $overlap
        ) {
            return null;
        }

        $schedule->update([

            'facility_id' =>
                $data['facility_id'],

            'event_date' =>
                $data['event_date'],

            'start_time' =>
                $data['start_time'],

            'end_time' =>
                $data['end_time'],

            'status' =>
                ScheduleStatus::RESCHEDULED->value
        ]);

        return $schedule;
    }
}