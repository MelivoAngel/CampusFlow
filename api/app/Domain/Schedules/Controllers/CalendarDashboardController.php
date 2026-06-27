<?php

namespace App\Domain\Schedules\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Schedules\Services\CalendarDashboardService;

class CalendarDashboardController
{
    public function index(
        Request $request,
        CalendarDashboardService $service
    ): JsonResponse
    {
        $analytics =
            $service->getAnalytics(

                $request->user()
            );

        return response()->json([

            'success' => true,

            'data' => $analytics
        ]);
    }
}