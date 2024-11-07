<?php

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\EmployeeApiController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);
Route::post('logout', [AuthenticationController::class, 'logout'])->middleware('auth:api');
Route::post('refresh', [AuthenticationController::class, 'refresh'])->middleware('auth:api');
Route::post('me', [AuthenticationController::class, 'me'])->middleware('auth:api');




Route::middleware('auth:api')->group(function () {
    Route::apiResource('employees', EmployeeApiController::class);
});

