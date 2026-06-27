<?php

namespace App\Domain\Schedules\Controllers;

use App\Domain\Schedules\Models\Schedule;
use App\Domain\Schedules\Policies\ScheduleCreationPolicy;
use App\Domain\Schedules\Policies\ScheduleEditPolicy;
use App\Domain\Schedules\Requests\CreateScheduleRequest;
use App\Domain\Schedules\Requests\UpdateScheduleRequest;
use App\Domain\Schedules\Services\ScheduleService;
use App\Domain\Schedules\Services\UpdateScheduleService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduleController
{
    public function index(
        Request $request
    ): JsonResponse
    {
        $user =
            $request->user();

        $query =
            Schedule::with([

                'campus',

                'creator',

                'facility'
            ]);

        if (
            $user->role !==
            'super_admin'
        ) {
            $query->where(

                'campus_id',

                $user->campus_id
            );
        }

        $schedules =
            $query->orderBy(

                'event_date'

            )->get();

        return response()->json([

            'success' => true,

            'data' => $schedules
        ]);
    }

    public function store(
        Request $request,
        CreateScheduleRequest $validator,
        ScheduleCreationPolicy $policy,
        ScheduleService $service
    ): JsonResponse
    {
        $creator =
            $request->user();

        if (
            ! $policy->canCreate(
                $creator
            )
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'You are not allowed to create schedules'

            ],403);
        }

        $validated =
            $validator->validate(

                $request->all(),

                $creator
            );

        $schedule =
            $service->create(

                $creator,

                $validated
            );

        if (! $schedule) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Facility already has a reservation during that time'

            ],422);
        }

        return response()->json([

            'success' => true,

            'message' =>
                'Schedule created successfully',

            'data' => $schedule

        ],201);
    }

    public function update(
        Request $request,
        int $id,
        UpdateScheduleRequest $validator,
        ScheduleEditPolicy $policy,
        UpdateScheduleService $service
    ): JsonResponse
    {
        $user =
            $request->user();

        $schedule =
            Schedule::find(
                $id
            );

        if (! $schedule) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Schedule not found'

            ],404);
        }

        if (
            ! $policy->canEdit(

                $user,

                $schedule
            )
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Access denied'

            ],403);
        }

        $validated =
            $validator->validate(
                $request->all()
            );

        $updated =
            $service->update(

                $schedule,

                $validated
            );

        if (! $updated) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Facility already has a reservation during that time'

            ],422);
        }

        return response()->json([

            'success' => true,

            'message' =>
                'Schedule updated successfully',

            'data' => $updated
        ]);
    }

    public function destroy(
        Request $request,
        int $id
    ): JsonResponse
    {
        $user =
            $request->user();

        $schedule =
            Schedule::find(
                $id
            );

        if (! $schedule) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Schedule not found'

            ],404);
        }

        if (

            $user->role !==
            'super_admin' &&

            $user->campus_id !==
            $schedule->campus_id
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Access denied'

            ],403);
        }

        $schedule->delete();

        return response()->json([

            'success' => true,

            'message' =>
                'Schedule deleted successfully'
        ]);
    }
}