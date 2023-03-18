<?php

use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Auth\{RegisterController, AuthController};
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (){
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/users', [UserController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

