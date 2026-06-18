<?php
use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Users\Controllers\UserController;
use App\Domain\Buildings\Controllers\BuildingController;

Route::prefix('v1')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });

    Route::middleware(['auth:sanctum','role:superadmin' ])->get('/test-admin', function () {
        return response()->json(['message' => 'Access granted']);
    });

    Route::middleware(['auth:sanctum'])->post('/users',[UserController::class, 'store']);
    Route::middleware(['auth:sanctum'])->post('/buildings',[BuildingController::class, 'store']);

});