<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\InterestController;
use Illuminate\Support\Facades\Route;

// Öffentlich – ohne Token erreichbar
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/auth/google', [GoogleAuthController::class, 'store']);
Route::get('/interests', [InterestController::class, 'index']);

// Geschützt – nur mit gültigem Sanctum-Token
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/activities', [ActivityController::class, 'index']);
    Route::post('/activities', [ActivityController::class, 'store']);
    Route::get('/activities/{activity}', [ActivityController::class, 'show']);
    Route::post('/activities/{activity}/join', [ActivityController::class, 'join']);
    Route::delete('/activities/{activity}/join', [ActivityController::class, 'leave']);
});
