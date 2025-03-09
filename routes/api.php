<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthBoatController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\MovementController;
use App\Http\Controllers\API\ProfileController;

Route::prefix('boat')->group(function () {
    // Route::post('/register', [AuthBoatController::class, 'register']);
    Route::post('/login', [AuthBoatController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthBoatController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('profiles', ProfileController::class);
    Route::post('/profiles/{profile}/claim', [ProfileController::class, 'claimProfile']);

    Route::apiResource('items', ItemController::class);

    Route::apiResource('movements', MovementController::class);

});
