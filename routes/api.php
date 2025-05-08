<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('events')->group(function () {
    Route::post('/', [EventController::class, 'store']);
    Route::get('/', [EventController::class, 'index']);
    Route::put('/{event}', [EventController::class, 'update']);
    Route::get('/{event}', [EventController::class, 'show']);
    Route::delete('/{event}', [EventController::class, 'destroy']);
});

Route::apiResource('attendees', AttendeeController::class);
Route::post('/bookings', [BookingController::class, 'store']);