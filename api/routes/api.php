<?php
use App\Domain\Auth\Controllers\AuthController;
use App\Domain\Users\Controllers\UserController;
use App\Domain\Buildings\Controllers\BuildingController;
use App\Domain\Meters\Controllers\MeterController;
use App\Domain\Meters\Controllers\MeterReadingController;
use App\Domain\Meters\Controllers\MeterAnomalyController;
use App\Domain\Campuses\Controllers\CampusController;


Route::prefix('v1')->group(function () {

    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });

    Route::middleware(['auth:sanctum','role:super_admin' ])->get('/test-admin', function () {
        return response()->json(['message' => 'Access granted']);
    });

    Route::middleware(['auth:sanctum'])->get('/users',[UserController::class, 'index']);
    Route::middleware(['auth:sanctum'])->post('/users',[UserController::class, 'store']);
    Route::middleware(['auth:sanctum'])->patch('/users/{id}',[UserController::class,'update']);
    Route::middleware(['auth:sanctum'])->post('/buildings',[BuildingController::class, 'store']);
    Route::middleware(['auth:sanctum'])->patch('/buildings/{id}',[BuildingController::class, 'update']);
    Route::middleware(['auth:sanctum'])->post('/meters',[MeterController::class, 'store']);
    Route::middleware(['auth:sanctum'])->get('/meters',[MeterController::class, 'index']);
    Route::middleware(['auth:sanctum'])->patch('/meters/{id}',[MeterController::class, 'update']);
    Route::middleware(['auth:sanctum'])->post('/meter-readings',[MeterReadingController::class, 'store']);
    Route::middleware(['auth:sanctum'])->get('/meter-readings',[MeterReadingController::class, 'index']);
    Route::middleware(['auth:sanctum'])->put('/meter-readings/{id}',[MeterReadingController::class, 'update']);
    Route::middleware(['auth:sanctum'])->patch('/meter-readings/{id}/correct',[MeterReadingController::class, 'correct']);
    Route::middleware(['auth:sanctum'])->patch('/meter-readings/{id}/approve',[MeterReadingController::class, 'approve']);
    Route::middleware(['auth:sanctum'])->get('/meter-anomalies',[MeterAnomalyController::class,'index']);
    Route::middleware(['auth:sanctum'])->patch('/meter-anomalies/{id}/resolve',[MeterAnomalyController::class,'resolve']);
    Route::middleware(['auth:sanctum'])->get('/campuses',[CampusController::class, 'index']);
    
});