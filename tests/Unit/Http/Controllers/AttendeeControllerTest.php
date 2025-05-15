<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use Mockery;
use App\Models\Attendee;
use App\Http\Controllers\Api\AttendeeController;
use App\Http\Requests\AttendeeRequest;
use App\Services\AttendeeService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendeeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_attendee_can_registeration()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];
        
        $attendeeModel = new Attendee($payload);
        // Mock the AttendeeRequest to validated data and return
        $request = Mockery::mock(AttendeeRequest::class);
        $request->shouldReceive('validated')
                ->once()
                ->andReturn($payload);

        $attendeeService = Mockery::mock(AttendeeService::class);
        $attendeeService->shouldReceive('create')
            ->once()
            ->with($payload)
            ->andReturn($attendeeModel);

        // Instantiate controller with mocked service
        $controller = new AttendeeController($attendeeService);

        $response = $controller->store($request); //called controller method

        // Assert status code
        $this->assertEquals(201, $response->getStatusCode());

        $data = $response->getData();
        $this->assertTrue(property_exists($data, 'data'));
        $this->assertTrue(property_exists($data->data, 'email'));
    }
}
