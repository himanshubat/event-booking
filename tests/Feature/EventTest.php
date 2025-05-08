<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Event;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_can_be_created()
    {
        
        $country = Country::factory()->create();    
        $payload = [
            'title' => 'Tech Conference',
            'description' => 'A big tech conference.',
            'country_id' => $country->id,
            'date' => '2025-07-01',
            'capacity' => 100
        ];

        $response = $this->postJson('/api/events', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Tech Conference']);
    }

    public function test_event_list_returns_events()
    {
        $country = Country::factory()->create();

        Event::factory()->count(3)->create([
            'country_id' => $country->id,
        ]);

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
                ->assertJsonCount(3, 'data');
    }
}
