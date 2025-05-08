<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Event;
use App\Models\Attendee;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_can_book_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        $payload = [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ];

        $response = $this->postJson('/api/bookings', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['event_id' => $event->id]);
    }

    public function test_prevent_duplicate_booking()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee = Attendee::factory()->create();

        // First booking
        $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        // Duplicate booking
        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        $response->assertStatus(422)
                 ->assertJsonFragment(['attendee_id' => ['This attendee has already booked the event.']]);
    }
}
