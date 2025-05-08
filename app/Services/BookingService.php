<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function createBooking(int $eventId, int $attendeeId): Booking
    {
        $event = Event::findOrFail($eventId);

        // Check for duplicate booking
        $alreadyBooked = Booking::where('event_id', $eventId)
                                ->where('attendee_id', $attendeeId)
                                ->exists();

        if ($alreadyBooked) {
            throw ValidationException::withMessages([
                'attendee_id' => 'This attendee has already booked the event.',
            ]);
        }

        // Check for overbooking
        $bookingCount = Booking::where('event_id', $eventId)->count();

        if ($bookingCount >= $event->capacity) {
            throw ValidationException::withMessages([
                'event_id' => 'This event is fully booked.',
            ]);
        }

        return Booking::create([
            'event_id' => $eventId,
            'attendee_id' => $attendeeId,
        ]);
    }
}
