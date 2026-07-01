<?php

namespace App\Domain\Dashboard\Services;

use Carbon\Carbon;
use App\Domain\Users\Models\User;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Schedules\Models\Schedule;

class AdminDashboardService
{
    public function get(
        User $user
    ): array
    {
        $readingsQuery =
            MeterReading::query();

        $anomalyQuery =
            MeterAnomaly::query();

        $scheduleQuery =
            Schedule::query();

        if (

            $user->role !==
            'super_admin'
        ) {

            $readingsQuery->whereHas(

                'meter',

                fn ($query) =>

                $query->where(

                    'campus_id',

                    $user->campus_id
                )
            );

            $anomalyQuery->whereHas(

                'meter',

                fn ($query) =>

                $query->where(

                    'campus_id',

                    $user->campus_id
                )
            );

            $scheduleQuery->where(

                'campus_id',

                $user->campus_id
            );
        }

        $waterConsumption =

            (clone $readingsQuery)

            ->whereMonth(

                'recorded_date',

                Carbon::now()->month

            )

            ->whereHas(

                'meter',

                fn ($query) =>

                $query->where(

                    'resource_type',

                    'water'
                )
            )

            ->sum(

                'consumption'
            );

        $electricityConsumption =

            (clone $readingsQuery)

            ->whereMonth(

                'recorded_date',

                Carbon::now()->month

            )

            ->whereHas(

                'meter',

                fn ($query) =>

                $query->where(

                    'resource_type',

                    'electricity'
                )
            )

            ->sum(

                'consumption'
            );

        return [

            'monthly_water_consumption' =>

                $waterConsumption,

            'monthly_electricity_consumption' =>

                $electricityConsumption,

            'monthly_waste' =>

                null,

            'readings_today' =>

                (clone $readingsQuery)

                ->whereDate(

                    'recorded_date',

                    Carbon::today()

                )->count(),

            'pending_readings' =>

                (clone $readingsQuery)

                ->where(

                    'is_approved',

                    false

                )->count(),

            'approved_readings' =>

                (clone $readingsQuery)

                ->where(

                    'is_approved',

                    true

                )->count(),

            'active_anomalies' =>

                (clone $anomalyQuery)

                ->where(

                    'is_resolved',

                    false

                )->count(),

            'events_today' =>

                (clone $scheduleQuery)

                ->whereDate(

                    'event_date',

                    Carbon::today()

                )->count(),

            'upcoming_events' =>

                (clone $scheduleQuery)

                ->where(

                    'status',

                    'upcoming'

                )->count(),

            'recent_anomalies' =>

                (clone $anomalyQuery)

                ->with([

                    'meter:id,name,meter_code'

                ])

                ->where(

                    'is_resolved',

                    false

                )->latest()

                ->take(5)

                ->get(),

            'pending_approvals' =>

                (clone $readingsQuery)

                ->with([

                    'technician:id,name',

                    'meter:id,name,meter_code'

                ])

                ->where(

                    'is_approved',

                    false

                )->latest()

                ->take(5)

                ->get()
        ];
    }
}