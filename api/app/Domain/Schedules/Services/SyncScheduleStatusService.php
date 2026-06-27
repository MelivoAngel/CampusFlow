<?php

namespace App\Domain\Schedules\Services;

use Carbon\Carbon;
use App\Domain\Schedules\Models\Schedule;
use App\Domain\Schedules\Enums\ScheduleStatus;

class SyncScheduleStatusService
{
    public function sync(): void
    {
        $schedules =
            Schedule::where(

                'status',

                '!=',

                ScheduleStatus::CANCELLED->value

            )->get();

        $now =
            Carbon::now();

        foreach (
            $schedules as $schedule
        ) {

            $startDateTime =
                Carbon::parse(

                    $schedule->event_date .

                    ' ' .

                    $schedule->start_time
                );

            $endDateTime =
                Carbon::parse(

                    $schedule->event_date .

                    ' ' .

                    $schedule->end_time
                );

            if (

                $now->lt(

                    $startDateTime
                )
            ) {
                $status =
                    ScheduleStatus::UPCOMING->value;
            }

            elseif (

                $now->between(

                    $startDateTime,

                    $endDateTime
                )
            ) {
                $status =
                    ScheduleStatus::ONGOING->value;
            }

            else {
                $status =
                    ScheduleStatus::COMPLETED->value;
            }

            if (

                $schedule->status !==
                $status
            ) {
                $schedule->update([

                    'status' =>
                        $status
                ]);
            }
        }
    }
}