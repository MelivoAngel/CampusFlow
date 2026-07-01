<?php

namespace App\Domain\Dashboard\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Domain\Dashboard\Services\AdminDashboardService;
use App\Domain\Dashboard\Services\CalendarDashboardService;

class DashboardController
{
    public function calendar(
        CalendarDashboardService $service
    ): JsonResponse
    {
        return response()->json([

            'success' => true,

            'data' =>

                $service->get()
        ]);
    }

    public function admin(
        Request $request,
        AdminDashboardService $service
    ): JsonResponse
    {
        return response()->json([

            'success' => true,

            'data' =>

                $service->get(

                    $request->user()
                )
        ]);
    }
}