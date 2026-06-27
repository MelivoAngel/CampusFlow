<?php

namespace App\Domain\Schedules\Services;

use App\Domain\Schedules\Models\Schedule;

class UpdateScheduleService
{
    public function update(
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

            $query->whereBetween(

                'start_time',

                [

                    $data['start_time'],

                    $data['end_time']
                ]

            )->orWhereBetween(

                'end_time',

                [

                    $data['start_time'],

                    $data['end_time']
                ]
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

            'organization' =>
                $data['organization'],

            'event_name' =>
                $data['event_name'],

            'event_date' =>
                $data['event_date'],

            'start_time' =>
                $data['start_time'],

            'end_time' =>
                $data['end_time'],

            'description' =>
                $data['description'] ?? null
        ]);

        return $schedule;
    }
}