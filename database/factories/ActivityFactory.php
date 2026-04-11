<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['running', 'cycling', 'swimming']),
            'distance' => fake()->randomFloat(2, 0.1, 100),
            'duration' => fake()->numberBetween(10, 300),
            'date' => fake()->date(),
            'notes' => fake()->sentence(),
            'weather_json' => json_encode([
                'temperature' => fake()->numberBetween(-10, 35),
                'conditions' => fake()->randomElement(['sunny', 'cloudy', 'rainy', 'snowy']),
            ]),
        ];
    }
}
