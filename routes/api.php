<?php

use App\Http\Controllers\Api\v1\{UserController, MovieController};
use App\Http\Controllers\Auth\{RegisterController, AuthController};
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (){
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::apiResource('/users', UserController::class);
    Route::apiResource('/movies', MovieController::class);
    Route::post('/logout', [AuthController::class, 'logout']);
});

