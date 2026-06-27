<?php

namespace App\Domain\Facilities\Controllers;

use App\Domain\Facilities\Models\Facility;
use App\Domain\Facilities\Policies\FacilityCreationPolicy;
use App\Domain\Facilities\Policies\FacilityEditPolicy;
use App\Domain\Facilities\Requests\CreateFacilityRequest;
use App\Domain\Facilities\Requests\UpdateFacilityRequest;
use App\Domain\Facilities\Services\FacilityService;
use App\Domain\Facilities\Services\UpdateFacilityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FacilityController
{
    public function index(
        Request $request
    ): JsonResponse
    {
        $user =
            $request->user();

        $query =
            Facility::with([

                'campus',

                'creator'
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

        $facilities =
            $query->get();

        return response()->json([

            'success' => true,

            'data' => $facilities
        ]);
    }

    public function store(
        Request $request,
        CreateFacilityRequest $validator,
        FacilityCreationPolicy $policy,
        FacilityService $service
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
                    'You are not allowed to create facilities'

            ],403);
        }

        $validated =
            $validator->validate(

                $request->all(),

                $creator
            );

        $facility =
            $service->create(

                $creator,

                $validated
            );

        return response()->json([

            'success' => true,

            'message' =>
                'Facility created successfully',

            'data' => $facility

        ],201);
    }

    public function update(
        Request $request,
        int $id,
        UpdateFacilityRequest $validator,
        FacilityEditPolicy $policy,
        UpdateFacilityService $service
    ): JsonResponse
    {
        $user =
            $request->user();

        $facility =
            Facility::find(
                $id
            );

        if (! $facility) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Facility not found'

            ],404);
        }

        if (
            ! $policy->canEdit(

                $user,

                $facility
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

        if (

            $facility->name ==
            $validated['name'] &&

            $facility->description ==
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

                $facility,

                $validated
            );

        return response()->json([

            'success' => true,

            'message' =>
                'Facility updated successfully',

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

        $facility =
            Facility::find(
                $id
            );

        if (! $facility) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Facility not found'

            ],404);
        }

        if (

            $user->role !==
            'super_admin' &&

            $user->campus_id !==
            $facility->campus_id
        ) {
            return response()->json([

                'success' => false,

                'message' =>
                    'Access denied'

            ],403);
        }

        $facility->delete();

        return response()->json([

            'success' => true,

            'message' =>
                'Facility deleted successfully'
        ]);
    }
}