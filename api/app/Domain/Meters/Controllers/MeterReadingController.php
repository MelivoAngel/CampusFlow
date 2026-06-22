<?php

namespace App\Domain\Meters\Controllers;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Meters\Requests\SubmitReadingRequest;
use App\Domain\Meters\Services\MeterReadingService;
use App\Domain\Meters\Services\ReadingValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Domain\Meters\Policies\ReadingEditPolicy;
use App\Domain\Meters\Requests\UpdateReadingRequest;
use App\Domain\Meters\Services\UpdateReadingService;
use App\Domain\Meters\Policies\CorrectReadingPolicy;
use App\Domain\Meters\Requests\CorrectReadingRequest;
use App\Domain\Meters\Services\CorrectionService;

class MeterReadingController
{
    public function store(
        Request $request,
        SubmitReadingRequest $validator,
        MeterReadingService $service,
        ReadingValidationService $readingValidator
    ): JsonResponse
    {
        $user = $request->user();

        if (
            $user->role !==
            'field_technician'
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Only technicians can edit submissions'
            ], 403);
        }

        if ($user->role !== 'field_technician') {
            return response()->json([
                'success' => false,
                'message' => 'Only field technicians can submit readings'
            ], 403);
        }

        $validated = $validator->validate(
            $request->all() + [
                'photo' => $request->file('photo')
            ]
        );

        $meter = Meter::find(
            $validated['meter_id']
        );

        if (
            $meter->campus_id !==
            $user->campus_id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot submit for this meter'
            ], 403);
        }

        if (! $meter->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Meter is inactive'
            ], 403);
        }

        $existing = MeterReading::where(
            'meter_id',
            $validated['meter_id']
        )->where(
            'recorded_date',
            now()->toDateString()
        )->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Reading for this meter has already been submitted today'
            ], 422);
        }

        $lastReading = $readingValidator->getLastReading(
            $validated['meter_id']
        );

        $hasAnomaly = $readingValidator->isAnomaly(
            $validated['current_reading'],
            $lastReading
        );

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $lastReading
        );

        $photoPath = $request->file(
            'photo'
        )->store(
            'meter-readings',
            'public'
        );

        $reading = $service->storeReading(
            $validated,
            $user,
            $lastReading,
            $hasAnomaly,
            $consumption,
            $photoPath
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading submitted successfully',
            'data' => $reading
        ]);
    }


    public function update(
        Request $request,
        int $id,
        UpdateReadingRequest $validator,
        ReadingEditPolicy $policy,
        UpdateReadingService $service
    ): JsonResponse
    {
        $user = $request->user();

        $reading = MeterReading::find($id);

        if (! $reading) {
            return response()->json([
                'success' => false,
                'message' => 'Reading not found'
            ], 404);
        }

        if (! $policy->canEdit($user, $reading)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot edit this reading'
            ], 403);
        }

        $validated = $validator->validate(
            $request->all() + [
                'photo' => $request->file('photo')
            ]
        );

        if (
            $reading->current_reading ==
            $validated['current_reading']
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No changes detected'
            ], 422);
        }

        $hasAnomaly = $service->isAnomaly(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $photoPath = $reading->photo_path;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('meter-readings', 'public');
        }

        $updateData = [

            'updated_by' => $user->id,

            'current_reading' => $validated['current_reading'],

            'consumption' => $consumption,

            'photo_path' => $photoPath,

            'has_anomaly' => $hasAnomaly,

            'anomaly_reason' => $hasAnomaly
                ? 'Reading lower than previous reading'
                : null
        ];

        $reading->update(
            $updateData
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading updated successfully',
            'data' => $reading
        ]);
    }

    public function correct(
        Request $request,
        int $id,
        CorrectReadingRequest $validator,
        CorrectReadingPolicy $policy,
        CorrectionService $service
    ): JsonResponse
    {
        $user = $request->user();

        $reading = MeterReading::find(
            $id
        );

        if (! $reading) {
            return response()->json([
                'success' => false,
                'message' => 'Reading not found'
            ], 404);
        }

        if (! $policy->canCorrect($user, $reading)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot correct this reading'
            ], 403);
        }

        $validated = $validator->validate(
            $request->all()
        );

        if (
            $reading->current_reading ==
            $validated['current_reading']
        ) {
            return response()->json([
                'success' => false,
                'message' => 'No changes detected'
            ], 422);
        }

        $hasAnomaly = $service->isAnomaly(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $reading->update([

            'approved_by' => $user->id,

            'updated_by' => $user->id,

            'current_reading' => $validated['current_reading'],

            'consumption' => $consumption,

            'has_anomaly' => $hasAnomaly,

            'anomaly_reason' => $hasAnomaly
                ? 'Reading lower than previous reading'
                : null,

            'is_approved' => true,

            'was_corrected' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reading corrected successfully',
            'data' => $reading
        ]);
    }
}