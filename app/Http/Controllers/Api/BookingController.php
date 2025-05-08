<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookEventRequest;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(BookEventRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking(
                $request->input('event_id'),
                $request->input('attendee_id')
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking successful.',
                'data' => $booking,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Booking failed due to validation errors.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
