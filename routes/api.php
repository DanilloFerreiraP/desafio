<?php

use App\Http\Controllers\Api\HealthUnitController;
use App\Http\Controllers\Api\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/health-units', [
    HealthUnitController::class,
    'index',
]);

Route::apiResource('patients', PatientController::class);
