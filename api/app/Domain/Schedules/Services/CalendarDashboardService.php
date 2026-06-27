<?php

namespace App\Domain\Schedules\Services;

use Carbon\Carbon;
use App\Domain\Schedules\Models\Schedule;
use App\Domain\Schedules\Enums\ScheduleStatus;
use App\Domain\Facilities\Models\Facility;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\DB;

class CalendarDashboardService
{
    public function getAnalytics(
        User $user
    ): array
    {
        $today =
            Carbon::today();

        $startOfWeek =
            Carbon::now()->startOfWeek();

        $endOfWeek =
            Carbon::now()->endOfWeek();

        $startOfMonth =
            Carbon::now()->startOfMonth();

        $endOfMonth =
            Carbon::now()->endOfMonth();

        $campusId =
            $user->campus_id;

        return [

            'reservations_today' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->whereDate(

                    'event_date',

                    $today

                )->count(),

            'reservations_week' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->whereBetween(

                    'event_date',

                    [

                        $startOfWeek,

                        $endOfWeek
                    ]

                )->count(),

            'reservations_month' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->whereBetween(

                    'event_date',

                    [

                        $startOfMonth,

                        $endOfMonth
                    ]

                )->count(),

            'upcoming' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->where(

                    'status',

                    ScheduleStatus::UPCOMING->value

                )->count(),

            'ongoing' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->where(

                    'status',

                    ScheduleStatus::ONGOING->value

                )->count(),

            'completed' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->where(

                    'status',

                    ScheduleStatus::COMPLETED->value

                )->count(),

            'cancelled' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->where(

                    'status',

                    ScheduleStatus::CANCELLED->value

                )->count(),

            'rescheduled' =>

                Schedule::where(

                    'campus_id',

                    $campusId

                )->where(

                    'status',

                    ScheduleStatus::RESCHEDULED->value

                )->count(),

            'top_facilities' =>

                Schedule::select(

                    'facility_id',

                    DB::raw(

                        'COUNT(*) as total'
                    )

                )->with(

                    'facility'

                )->where(

                    'campus_id',

                    $campusId

                )->whereBetween(

                    'event_date',

                    [

                        $startOfMonth,

                        $endOfMonth
                    ]

                )->groupBy(

                    'facility_id'
                )

                ->orderByDesc(

                    'total'
                )

                ->limit(5)

                ->get(),

            'upcoming_events' =>

                Schedule::with(

                    'facility'
                )

                ->where(

                    'campus_id',

                    $campusId

                )->whereBetween(

                    'event_date',

                    [

                        $today,

                        $endOfMonth
                    ]

                )->where(

                    'status',

                    ScheduleStatus::UPCOMING->value

                )

                ->orderBy(

                    'event_date'
                )

                ->limit(5)

                ->get(),

            'today_events' =>

                Schedule::with(

                    'facility'
                )

                ->where(

                    'campus_id',

                    $campusId

                )->whereDate(

                    'event_date',

                    $today

                )

                ->orderBy(

                    'start_time'
                )

                ->get()
        ];
    }
}