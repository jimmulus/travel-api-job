<?php

use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\TourController;
use App\Http\Controllers\Api\V1\TravelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/travels', [TravelController::class, 'index']);
Route::get('/travels/{travel:slug}/tours', TourController::class);

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'store']);
    Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'destroy']);
});

Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::middleware('role:admin')->group(function () {
        Route::post('/travels', [Admin\TravelController::class, 'store']);
        Route::post('/travels/{travel:slug}/tours', [Admin\TourController::class, 'store']);
        Route::get('/travels/{travel:slug}/tours', Admin\TravelTourController::class);
        Route::get('/tours', [Admin\TourController::class, 'index']);
    });
    Route::middleware('role:editor')->group(function () {
        Route::put('/travels/{travel:slug}', [Admin\TravelController::class, 'update']);
        Route::get('/travels', [Admin\TravelController::class, 'index']);
    });
});
