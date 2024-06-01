<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dive>
 */
class DiveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'date' => $this->faker->date,
            'time' => $this->faker->time,
            'image' => $this->faker->imageUrl(),
            'user_id' => 1
        ];
    }
}
