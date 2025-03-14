<?php

namespace Database\Factories;

use App\Models\Itinerary;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryFactory extends Factory
{
    protected $model = Itinerary::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'category' => $this->faker->word,
            'duration' => $this->faker->numberBetween(1, 10),
            'image' => 'default.png',
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
