<?php

namespace App\Domain\Meters\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Domain\Meters\Models\MeterAnomaly;

use App\Domain\Meters\Requests\ResolveAnomalyRequest;

use App\Domain\Meters\Policies\ViewAnomaliesPolicy;
use App\Domain\Meters\Policies\ResolveAnomalyPolicy;

use App\Domain\Meters\Services\GetAnomaliesService;
use App\Domain\Meters\Services\ManualResolveAnomalyService;

class MeterAnomalyController
{
    public function index(
        Request $request,
        ViewAnomaliesPolicy $policy,
        GetAnomaliesService $service
    ): JsonResponse
    {
        $user = $request->user();

        if (! $policy->canView($user)) {
            return response()->json([

                'success' => false,

                'message' => 'You are not allowed to view anomalies'
            ],403);
        }

        $anomalies = $service->get(
            $user
        );

        return response()->json([

            'success' => true,

            'data' => $anomalies
        ]);
    }

    public function resolve(
        Request $request,
        int $id,
        ResolveAnomalyRequest $validator,
        ResolveAnomalyPolicy $policy,
        ManualResolveAnomalyService $service
    ): JsonResponse
    {
        $user = $request->user();

        $anomaly = MeterAnomaly::find(
            $id
        );

        if (! $anomaly) {
            return response()->json([

                'success' => false,

                'message' => 'Anomaly not found'
            ],404);
        }

        if (! $policy->canResolve(

            $user,

            $anomaly
        )) {
            return response()->json([

                'success' => false,

                'message' => 'You cannot resolve anomalies'
            ],403);
        }

        $validated = $validator->validate(
            $request->all()
        );

        $service->resolve(

            $anomaly,

            $user,

            $validated['resolution_note']
        );

        return response()->json([

            'success' => true,

            'message' => 'Anomaly resolved successfully'
        ]);
    }
}