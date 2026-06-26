<?php

namespace App\Domain\Buildings\Controllers;

use App\Domain\Buildings\Models\Building;
use App\Domain\Buildings\Policies\BuildingCreationPolicy;
use App\Domain\Buildings\Policies\BuildingEditPolicy;
use App\Domain\Buildings\Requests\CreateBuildingRequest;
use App\Domain\Buildings\Requests\UpdateBuildingRequest;
use App\Domain\Buildings\Services\BuildingService;
use App\Domain\Buildings\Services\UpdateBuildingService;
use App\Domain\Meters\Models\Meter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuildingController
{
    public function index(
        Request $request
    ): JsonResponse
    {
        $user =
            $request->user();

        $query =
            Building::with([

                'campus',

                'creator',

                'meters'

            ])->withCount(
                'meters'
            );

        if (
            $user->role !==
            'super_admin'
        ) {
            $query->where(

                'campus_id',

                $user->campus_id
            );
        }

        $buildings =
            $query->get();

        return response()->json([

            'success' => true,

            'data' => $buildings
        ]);
    }

    public function store(
        Request $request,
        CreateBuildingRequest $validator,
        BuildingCreationPolicy $policy,
        BuildingService $service
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
                    'You are not allowed to create buildings'

            ],403);
        }

        $validated =
            $validator->validate(

                $request->all(),

                $creator
            );

        $building =
            $service->createBuilding(

                $creator,

                $validated
            );

        return response()->json([

            'success' => true,

            'message' =>
                'Building created successfully',

            'data' => $building

        ],201);
    }

    public function update(
        Request $request,
        int $id,
        UpdateBuildingRequest $validator,
        BuildingEditPolicy $policy,
        UpdateBuildingService $service
    ): JsonResponse
    {
        $user =
            $request->user();

        $building =
            Building::find(
                $id
            );

        if (! $building) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Building not found'

            ],404);
        }

        if (
            ! $policy->canEdit(

                $user,

                $building
            )
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'You cannot edit this building'

            ],403);
        }

        $validated =
            $validator->validate(
                $request->all()
            );

        if (

            $building->name ==
            $validated['name'] &&

            $building->description ==
            (
                $validated['description']
                ?? null
            )
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'No changes detected'

            ],422);
        }

        $updated =
            $service->update(

                $building,

                $validated
            );

        return response()->json([

            'success' => true,

            'message' =>
                'Building updated successfully',

            'data' => $updated
        ]);
    }

    public function availableMeters(
        Request $request,
        int $id
    ): JsonResponse
    {
        $user =
            $request->user();

        $building =
            Building::find(
                $id
            );

        if (! $building) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Building not found'

            ],404);
        }

        if (

            $user->role !==
            'super_admin' &&

            $building->campus_id !==
            $user->campus_id
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Access denied'

            ],403);
        }

        $meters =
            Meter::where(

                'campus_id',

                $building->campus_id

            )->where(

                'is_active',

                true

            )->select(

                'id',

                'name',

                'meter_code',

                'resource_type'

            )->get();

        return response()->json([

            'success' => true,

            'data' => $meters
        ]);
    }

    public function assignMeters(
        Request $request,
        int $id
    ): JsonResponse
    {
        $user =
            $request->user();

        $building =
            Building::find(
                $id
            );

        if (! $building) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Building not found'

            ],404);
        }

        if (

            $user->role !==
            'super_admin' &&

            $building->campus_id !==
            $user->campus_id
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Access denied'

            ],403);
        }

        $meterIds =
            $request->meter_ids ?? [];

        $validMeters =
            Meter::whereIn(

                'id',

                $meterIds

            )->where(

                'campus_id',

                $building->campus_id

            )->count();

        if (
            $validMeters !==
            count($meterIds)
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Invalid meter selection'

            ],422);
        }

        $syncData = [];

        foreach (
            $meterIds as $meterId
        ) {
            $syncData[
                $meterId
            ] = [

                'assigned_by' =>
                    $user->id
            ];
        }

        $building
            ->meters()
            ->sync(
                $syncData
            );

        return response()->json([

            'success' => true,

            'message' =>
                'Meters assigned successfully'
        ]);
    }

    public function destroy(Request $request,int $id): JsonResponse
    {
        $user = $request->user();

        $building = Building::with(
            'meters'
        )->find($id);

        if (! $building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ],404);
        }

        $forceDelete =
            $request->force === 'true';

        if (

            $building->meters()->exists()

            &&

            ! $forceDelete
        ) {
            return response()->json([

                'success' => false,

                'requires_confirmation' => true,

                'message' =>
                    'Building has assigned meters',

                'meters' =>
                    $building->meters
            ],422);
        }

        $building
            ->meters()
            ->detach();

        $building->delete();

        return response()->json([

            'success' => true,

            'message' =>
                'Building deleted successfully'
        ]);
    }
}