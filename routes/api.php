<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Controllers\Api\BookingController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('{id}', [EventController::class, 'show'])->name('show');
    Route::put('{id}', [EventController::class, 'update'])->name('update');
    Route::delete('{id}', [EventController::class, 'destroy'])->name('destroy');
});

// Attendee Routes
Route::prefix('attendees')->name('attendees.')->group(function () {
    Route::get('/', [AttendeeController::class, 'index'])->name('index');
    Route::post('/', [AttendeeController::class, 'store'])->name('store');
    Route::get('{id}', [AttendeeController::class, 'show'])->name('show');
    Route::put('{id}', [AttendeeController::class, 'update'])->name('update');
    Route::delete('{id}', [AttendeeController::class, 'destroy'])->name('destroy');
});

// Booking Route
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');