<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
use App\Http\Requests\BookEventRequest;
use App\Http\Resources\BookingResource;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    use ApiResponseTrait;
    protected BookingService $bookingService;
    protected string $resourceName = 'Booking';

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
            $booking->load(['event', 'attendee']);
            return $this->successResponse(
                new BookingResource($booking),
                "{$this->resourceName} successful.",
                201
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                "{$this->resourceName} failed due to validation errors.",
                $e->errors() ?: json_decode($e->getResponse()->getContent(), true)['errors'],
                422
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                "An unexpected error occurred while processing {$this->resourceName}.",
                ['exception' => $e->getMessage()],
                500
            );
        }
    }
}
