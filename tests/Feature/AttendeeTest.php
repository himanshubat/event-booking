<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendee_can_register()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];

        $response = $this->postJson('/api/attendees', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'john@example.com']);
    }
}
