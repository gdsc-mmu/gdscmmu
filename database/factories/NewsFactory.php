<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */


    // this is used to generate fake data for the news model, it uses the faker library to generate random data for the title, description, date and user_id fields
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'date' => $this->faker->date,
            
            'user_id' => 1
        ];
    }
}