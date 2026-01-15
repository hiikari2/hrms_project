<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AhpController;
use App\Http\Controllers\Api\DashboardController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Dashboard routes
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
    Route::get('/dashboard/charts', [DashboardController::class, 'getCharts']);

    // Employee routes
    Route::prefix('employees')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/{id}', [EmployeeController::class, 'show']);
        Route::put('/{id}', [EmployeeController::class, 'update']);
        Route::delete('/{id}', [EmployeeController::class, 'destroy']);
    });

    // AHP routes
    Route::prefix('ahp')->group(function () {
        Route::get('/kriteria', [AhpController::class, 'getKriteria']);
        Route::get('/sessions', [AhpController::class, 'getSessions']);
        Route::post('/sessions', [AhpController::class, 'createSession']);
        Route::post('/sessions/{id}/comparisons', [AhpController::class, 'savePairwiseComparisons']);
        Route::post('/sessions/{id}/calculate', [AhpController::class, 'calculate']);
        Route::get('/sessions/{id}/results', [AhpController::class, 'getResults']);
    });
});