<?php

namespace App\Domain\Dashboard\Services;

use Carbon\Carbon;
use App\Domain\Users\Models\User;
use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Meters\Models\MeterAnomaly;
use App\Domain\Campuses\Models\Campus;
use App\Domain\Buildings\Models\Building;

class AdminDashboardService
{
    public function get(): array
    {
        return [

            'total_campuses' =>

                Campus::count(),

            'total_buildings' =>

                Building::count(),

            'total_meters' =>

                Meter::count(),

            'total_users' =>

                User::count(),

            'readings_today' =>

                MeterReading::whereDate(

                    'recorded_date',

                    Carbon::today()

                )->count(),

            'pending_readings' =>

                MeterReading::where(

                    'is_approved',

                    false

                )->count(),

            'approved_readings' =>

                MeterReading::where(

                    'is_approved',

                    true

                )->count(),

            'detected_anomalies' =>

                MeterAnomaly::where(

                    'is_resolved',

                    false

                )->count(),

            'recent_anomalies' =>

                MeterAnomaly::with([

                    'meter:id,name,meter_code'

                ])->where(

                    'is_resolved',

                    false

                )->latest()

                ->take(5)

                ->get(),

            'pending_approvals' =>

                MeterReading::with([

                    'technician:id,name',

                    'meter:id,name,meter_code'

                ])->where(

                    'is_approved',

                    false

                )->latest()

                ->take(5)

                ->get()
        ];
    }
}