<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Booking;
use App\Http\Controllers\Api\BookingController;
use App\Http\Requests\BookEventRequest;
use App\Services\BookingService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_can_book_event()
    {
        // Arrange: Create event and attendee
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        // Create a fake Booking model instance to return
        $fakeBooking = new Booking([
            'id' => 1,
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);
        $fakeBooking->exists = true; // mark as saved

        // Mock BookingService and its createBooking method
        $bookingService = Mockery::mock(BookingService::class);
        $bookingService->shouldReceive('createBooking')
            ->once()
            ->with($event->id, $attendee->id)
            ->andReturn($fakeBooking);

        // Instantiate controller with mocked service
        $controller = new BookingController($bookingService);

        // Mock BookEventRequest and expect input() calls
        $request = Mockery::mock(BookEventRequest::class);
        $request->shouldReceive('input')
            ->with('event_id')
            ->andReturn($event->id);
        $request->shouldReceive('input')
            ->with('attendee_id')
            ->andReturn($attendee->id);

        // Call the store method
        $response = $controller->store($request);

        $responseData = $response->getData(true); // check the response
        
        // Asserting the status code and response data into the key
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($responseData['success']);
        $this->assertEquals('Booking successful.', $responseData['message']);
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('event', $responseData['data']);
        $this->assertArrayHasKey('attendee', $responseData['data']);
    }

    public function test_prevent_duplicate_booking()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee = Attendee::factory()->create();

        // Mock BookingService to throw ValidationException wtih custom response
        $bookingService = Mockery::mock(BookingService::class);
        $bookingService->shouldReceive('createBooking')
            ->once()
            ->with($event->id, $attendee->id)
            ->andThrow(new ValidationException(
                validator: validator([], []),
                response: response()->json([
                    'success' => false,
                    'message' => 'Booking failed due to validation errors.',
                    'errors' => [
                        'attendee_id' => ['This attendee has already booked the event.']
                    ]
                ], 422)
            ));

        $controller = new BookingController($bookingService);

        $request = Mockery::mock(BookEventRequest::class);
        $request->shouldReceive('input')->with('event_id')->andReturn($event->id);
        $request->shouldReceive('input')->with('attendee_id')->andReturn($attendee->id);

        $response = $controller->store($request); // call the controller method and recieve the response

        // Assert HTTP status code 422
        $this->assertEquals(422, $response->getStatusCode());

        $responseData = $response->getData(true); // retrieve the resposne

        // Asserting the status code and response data
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertFalse($responseData['success']);
        $this->assertEquals('Booking failed due to validation errors.', $responseData['message']);
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('attendee_id', $responseData['errors']);
        $this->assertEquals(
            ['This attendee has already booked the event.'],
            $responseData['errors']['attendee_id']
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
