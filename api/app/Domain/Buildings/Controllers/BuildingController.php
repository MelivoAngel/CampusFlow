<?php

namespace App\Domain\Buildings\Controllers;

use App\Domain\Buildings\Policies\BuildingCreationPolicy;
use App\Domain\Buildings\Requests\CreateBuildingRequest;
use App\Domain\Buildings\Services\BuildingService;
use App\Domain\Buildings\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Buildings\Requests\UpdateBuildingRequest;
use App\Domain\Buildings\Policies\BuildingEditPolicy;
use App\Domain\Buildings\Services\UpdateBuildingService;

class BuildingController
{
    public function store(
        Request $request,
        CreateBuildingRequest $createBuildingRequest,
        BuildingCreationPolicy $policy,
        BuildingService $service
    )
    {
        $creator = auth()->user();

        $validated = $createBuildingRequest->validate(
            $request->all(),
            $creator
        );

        if (
            !$policy->canCreate(
                $creator
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to create buildings'
            ], 403);
        }

        $building = $service->createBuilding(
            $creator,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Building created successfully',
            'data' => $building
        ], 201);
    }

    public function update(
        Request $request,
        int $id,
        UpdateBuildingRequest $validator,
        BuildingEditPolicy $policy,
        UpdateBuildingService $service
    ): JsonResponse
    {
        $user = $request->user();

        $building = Building::find(
            $id
        );

        if (! $building) {
            return response()->json([
                'success' => false,
                'message' => 'Building not found'
            ], 404);
        }

        if (
            ! $policy->canEdit(
                $user,
                $building
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot edit this building'
            ], 403);
        }

        $validated = $validator->validate(
            $request->all()
        );

        if (
            $building->name ==
            $validated['name'] &&

            $building->code ==
            $validated['code'] &&

            $building->type ==
            $validated['type'] &&

            $building->description ==
            ($validated['description'] ?? null)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No changes detected'
            ], 422);
        }

        $updated = $service->update(
            $building,
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Building updated successfully',
            'data' => $updated
        ]);
    }
}