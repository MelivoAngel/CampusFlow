<?php

namespace App\Domain\Meters\Controllers;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Policies\MeterCreationPolicy;
use App\Domain\Meters\Requests\CreateMeterRequest;
use App\Domain\Meters\Services\MeterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domain\Meters\Policies\ViewMetersPolicy;
use App\Domain\Meters\Services\GetMetersService;
use App\Domain\Meters\Requests\UpdateMeterRequest;
use App\Domain\Meters\Policies\UpdateMeterPolicy;
use App\Domain\Meters\Services\UpdateMeterService;
use App\Domain\Meters\Models\MeterAssignment;
use App\Domain\Users\Models\User;

class MeterController
{
    public function store(
        Request $request,
        CreateMeterRequest $validator,
        MeterCreationPolicy $policy,
        MeterService $service
    ): JsonResponse
    {
        $user = $request->user();

        if (! $policy->canCreate($user)) {
            return response()->json(['success' => false,'message' => 'You are not allowed to create meters'], 403);
        }

        $validated = $validator->validate($request->all(),$user);
        $campusId = $service->resolveCampusId($user,$validated['campus_id'] ?? null);

        if ($service->meterCodeExists($campusId,$validated['meter_code'])) {
            return response()->json(['success' => false,'message' => 'Meter code already exists in this campus'], 422);
        }

        $meter = Meter::create([
            'campus_id' => $campusId,
            'created_by' => $user->id,
            'resource_type' => $validated['resource_type'],
            'meter_code' => $validated['meter_code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null
        ]);

        return response()->json(['success' => true,'message' => 'Meter created successfully','data' => $meter]);
    }

    public function index(
        Request $request,
        ViewMetersPolicy $policy,
        GetMetersService $service
    ): JsonResponse
    {
        $user = $request->user();

        if (
            ! $policy->canView(
                $user
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ],403);
        }

        $meters = $service->get(
            $user
        );

        return response()->json([
            'success' => true,
            'data' => $meters
        ]);
    }

    public function mobile(
        Request $request
    ): JsonResponse
    {
        $user = $request->user();

        if (
            $user->role !==
            'field_technician'
        ) {
            return response()->json([

                'success' => false,

                'message' => 'Access denied'
            ],403);
        }

        $meters = Meter::whereHas(

            'assignment',

            fn ($query) => $query->where(

                'technician_id',

                $user->id
            )

        )->where(

            'is_active',

            true

        )->select(

            'id',

            'name',

            'resource_type'

        )->get();

        return response()->json([

            'success' => true,

            'data' => $meters
        ]);
    }

    public function update(
        Request $request,
        int $id,
        UpdateMeterRequest $validator,
        UpdateMeterPolicy $policy,
        UpdateMeterService $service
    ): JsonResponse
    {
        $user = $request->user();

        $meter = Meter::findOrFail(
            $id
        );

        if (
            ! $policy->canUpdate(
                $user,
                $meter
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ],403);
        }

        $validated =
            $validator->validate(
                $request->all()
            );

        $updated =
            $service->update(
                $meter,
                $validated
            );

        return response()->json([
            'success' => true,
            'message' => 'Meter updated successfully',
            'data' => $updated
        ]);
    }

    public function assign(
        Request $request,
        int $id
    ): JsonResponse
    {
        $user = $request->user();

        if (
            ! in_array(

                $user->role,

                ['campus_admin','staff']
            )
        ) {
            return response()->json([

                'success' => false,

                'message' => 'Access denied'
            ],403);
        }

        $meter = Meter::findOrFail(
            $id
        );

        if (
            $user->campus_id !==
            $meter->campus_id &&
            $user->role !==
            'super_admin'
        ) {
            return response()->json([

                'success' => false,

                'message' => 'You cannot assign this meter'
            ],403);
        }

        $technician = User::where(

            'id',
            $request->technician_id

        )->where(

            'role',
            'field_technician'

        )->first();

        if (! $technician) {
            return response()->json([

                'success' => false,

                'message' => 'Invalid technician'
            ],422);
        }

        if (
            $technician->campus_id !==
            $meter->campus_id
        ) {
            return response()->json([

                'success' => false,

                'message' => 'Technician must belong to same campus'
            ],422);
        }

        MeterAssignment::updateOrCreate(

            [
                'meter_id' => $meter->id
            ],

            [
                'technician_id' => $technician->id,

                'assigned_by' => $user->id
            ]
        );

        return response()->json([

            'success' => true,

            'message' => 'Meter assigned successfully'
        ]);
    }
}