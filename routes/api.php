<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TypeController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\AuthBoatController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\MovementController;
use App\Http\Controllers\API\StorageBoxController;


    // Route::post('/register', [AuthBoatController::class, 'register']);
    Route::post('/login', [AuthBoatController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthBoatController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('profiles', ProfileController::class);
    Route::post('/profiles/{profile}/claim', [ProfileController::class, 'claimProfile']);

    Route::apiResource('items', ItemController::class);

    Route::apiResource('movements', MovementController::class);

    Route::apiResource('/types', TypeController::class);
    Route::apiResource('/locations', LocationController::class);
    Route::get('/locations/{id}/items', [LocationController::class, 'getItems']);

    Route::apiResource('/boxes', StorageBoxController::class);
    Route::get('/roles', [RoleController::class, 'index']);

});
