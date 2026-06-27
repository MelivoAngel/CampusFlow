<?php

namespace App\Domain\Schedules\Services;

use App\Domain\Schedules\Enums\SchedulePermission;
use App\Domain\Schedules\Models\Schedule;
use App\Domain\Users\Models\User;

class ScheduleService
{
    public function resolveCampusId(
        User $creator,
        ?int $requestedCampusId
    ): ?int
    {
        if (
            SchedulePermission::requiresManualCampus(
                $creator->role
            )
        ) {
            return $requestedCampusId;
        }

        return $creator->campus_id;
    }

    public function create(
        User $creator,
        array $data
    ): ?Schedule
    {
        $campusId = $this->resolveCampusId(

            $creator,

            $data['campus_id'] ?? null
        );

        $overlap = Schedule::where(

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

        return Schedule::create([

            'campus_id' => $campusId,

            'created_by' => $creator->id,

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
    }
}