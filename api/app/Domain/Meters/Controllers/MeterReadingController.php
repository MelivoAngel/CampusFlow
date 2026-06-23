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
use App\Domain\Meters\Policies\ApproveReadingPolicy;
use App\Domain\Meters\Services\ApproveReadingService;
use App\Domain\Meters\Services\AnomalyDetectionService;
use App\Domain\Meters\Services\AnomalyService;
use App\Domain\Meters\Services\AnomalyLockService;
use App\Domain\Meters\Services\ResolveAnomalyService;
use App\Domain\Meters\Policies\ViewMeterReadingsPolicy;
use App\Domain\Meters\Services\GetMeterReadingsService;

class MeterReadingController
{
    public function store(
        Request $request,
        SubmitReadingRequest $validator,
        MeterReadingService $service,
        ReadingValidationService $readingValidator,
        AnomalyDetectionService $detector,
        AnomalyService $anomalyService
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

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $lastReading
        );

        $anomaly = $detector->detect(
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
            $consumption,
            $photoPath
        );

        if ($anomaly) {
            $anomalyService->create(
                $reading,
                $user,
                $anomaly
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Reading submitted successfully',
            'data' => $reading
        ]);
    }
    public function index(
        Request $request,
        ViewMeterReadingsPolicy $policy,
        GetMeterReadingsService $service
    ): JsonResponse
    {
        $user = $request->user();

        if (! $policy->canView($user)) {

            return response()->json([

                'success' => false,

                'message' => 'You are not allowed to view readings'
            ],403);
        }

        $readings = $service->get(
            $user
        );

        return response()->json([

            'success' => true,

            'data' => $readings
        ]);
    }

    public function update(
        Request $request,
        int $id,
        UpdateReadingRequest $validator,
        ReadingEditPolicy $policy,
        UpdateReadingService $service,
        AnomalyDetectionService $detector,
        AnomalyService $anomalyService
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

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $fakeLastReading = new MeterReading();

        $fakeLastReading->current_reading =
            $reading->previous_reading;

        $fakeLastReading->consumption =
            $reading->consumption;

        $anomaly = $detector->detect(
            $validated['current_reading'],
            $fakeLastReading
        );

        $photoPath = $reading->photo_path;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('meter-readings', 'public');
        }

        $reading->update([

            'updated_by' => $user->id,

            'current_reading' => $validated['current_reading'],

            'consumption' => $consumption,

            'photo_path' => $photoPath
        ]);

        if ($anomaly) {
            $anomalyService->create(
                $reading,
                $user,
                $anomaly
            );
        }

        if (! $anomaly) {
            $anomalyService->resolveExisting(
                $reading,
                $user
            );
        }

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
        CorrectionService $service,
        ResolveAnomalyService $resolver
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

        $consumption = $service->calculateConsumption(
            $validated['current_reading'],
            $reading->previous_reading
        );

        $reading->update([

            'approved_by' => $user->id,

            'updated_by' => $user->id,

            'current_reading' => $validated['current_reading'],

            'consumption' => $consumption,

            'is_approved' => true,

            'was_corrected' => true
        ]);

        $resolver->resolveByReading(
            $reading->id,
            $user
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading corrected successfully',
            'data' => $reading
        ]);
    }

    public function approve(
        Request $request,
        int $id,
        ApproveReadingPolicy $policy,
        ApproveReadingService $service,
        AnomalyLockService $lockService
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

        if (! $policy->canApprove($user, $reading)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot approve this reading'
            ], 403);
        }

        if (
            $lockService->hasUnresolvedAnomaly(
                $reading->meter_id
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'This meter has unresolved anomalies. Resolve anomaly before approval'
            ], 422);
        }

        if (
            $service->isAlreadyApproved(
                $reading
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Reading is already approved'
            ], 422);
        }

        $reading->update([

            'approved_by' => $user->id,

            'updated_by' => $user->id,

            'is_approved' => true,

            'was_corrected' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reading approved successfully',
            'data' => $reading
        ]);
    }
}