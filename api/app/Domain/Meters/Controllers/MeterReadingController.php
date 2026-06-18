<?php

namespace App\Domain\Meters\Controllers;

use App\Domain\Meters\Models\Meter;
use App\Domain\Meters\Models\MeterReading;
use App\Domain\Meters\Requests\SubmitReadingRequest;
use App\Domain\Meters\Services\MeterReadingService;
use App\Domain\Meters\Services\ReadingValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}