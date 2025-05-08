<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'country_id' =>  Country::query()->inRandomOrder()->value('id') ?? Country::factory()->create()->id,
            'date' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'capacity' => $this->faker->numberBetween(10, 100)
        ];
    }
}
