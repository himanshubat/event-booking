<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Country;
use App\Models\Event;
use App\Http\Controllers\Api\EventController;
use App\Http\Requests\EventRequest;
use App\Services\EventService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_can_be_created()
    {
        // create the instance of country
        $country = Country::factory()->create();

        // payload
        $payload = [
            'title' => 'Technical Conference',
            'description' => 'A large Technical conference.',
            'country_id' => $country->id,
            'date' => '2025-07-01',
            'capacity' => 10
        ];

        // Create a mock Model for event
        $event = new Event($payload);

        // Mock the EventService
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('create')
            ->once()
            ->with($payload)
            ->andReturn($event);

        // Instantiate the controller with the mocked service
        $controller = new EventController($eventService);

        // Mock the EventRequest
        $request = Mockery::mock(EventRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($payload);

        $response = $controller->store($request);

        $data = $response->getData(); // response
        // Assert status code
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue(property_exists($data, 'data'));
        $this->assertTrue(property_exists($data->data, 'title'));
        $this->assertEquals('Technical Conference', $data->data->title);
    }

    public function test_event_list_returns_events()
    {
        $country = Country::factory()->create();
        $countryId = is_array($country) ? $country['id'] : $country->id;
        $event1 = Event::factory()->make(['title' => 'Mock Event 1', 'country_id' => $country->id]);
        $event2 = Event::factory()->make(['title' => 'Mock Event 2', 'country_id' => $country->id]);

        // Convert array to a Collection
        $eventsCollection = collect([$event1, $event2]);

        // Create a LengthAwarePaginators
        $paginator = new LengthAwarePaginator(
            $eventsCollection, // current page
            $eventsCollection->count(), // total items
            10, // per page
            1,  // current page
            ['path' => url('/events')] // base URL for pagination links
        );

        // Mock the EventService
        $eventService = Mockery::mock(EventService::class);
        $eventService->shouldReceive('getAll')
            ->once()
            ->andReturn($paginator);

        // Instantiate the controller with the mocked service
        $controller = new EventController($eventService);

        // Act: Call the index method
        $response = $controller->index();

        // Assert status code
        $this->assertEquals(200, $response->getStatusCode());

        // Check count of items inside paginator
        $data = $response->getData(true);
        $this->assertCount(2, $data['data']); // 'data' key contains paginated items
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
