<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageStoreController;
use App\Http\Controllers\ImageTransformController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/images', ImageStoreController::class);
    Route::post('/resize', [ImageTransformController::class, 'resize']);
});
