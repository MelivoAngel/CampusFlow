<?php

namespace App\Domain\Campuses\Controllers;

use Illuminate\Http\JsonResponse;
use App\Domain\Campuses\Services\GetCampusesService;

class CampusController
{
    public function index(
        GetCampusesService $service
    ): JsonResponse
    {
        $campuses = $service->get();

        return response()->json([
            'success' => true,
            'data' => $campuses
        ]);
    }
}