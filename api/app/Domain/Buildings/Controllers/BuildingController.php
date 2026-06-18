<?php

namespace App\Domain\Buildings\Controllers;

use App\Domain\Buildings\Policies\BuildingCreationPolicy;
use App\Domain\Buildings\Requests\CreateBuildingRequest;
use App\Domain\Buildings\Services\BuildingService;
use Illuminate\Http\Request;

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
}