<?php

use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\EmployeeApiController;
use Illuminate\Support\Facades\Route;


// Public routes
Route::post('register', [AuthenticationController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

// Protected routes
Route::middleware(['jwt.auth'])->group(function () {
    // Route::get('me', [AuthenticationController::class, 'me']);
    Route::Resource('employees', EmployeeApiController::class);
});

// Public routes
// Route::post('/register', [AuthenticationController::class, 'register']);
// Route::post('/login', [AuthenticationController::class, 'login']);

// Protected routes using the jwt.auth middleware
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/logout', [AuthenticationController::class, 'logout']);
//     Route::get('/me', [AuthenticationController::class, 'me']);
//     Route::post('/refresh', [AuthenticationController::class, 'refresh']);
    
//     // Protected resource routes
//     Route::Resource('employees', EmployeeApiController::class);
// });