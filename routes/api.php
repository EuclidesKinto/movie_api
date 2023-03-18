<?php

use App\Http\Controllers\Auth\{RegisterController, AuthController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (){
    Route::post('/register', [RegisterController::class, 'store']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
});

