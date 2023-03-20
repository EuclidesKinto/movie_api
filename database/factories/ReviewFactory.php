<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *'name', 'rating', 'comment', 'user_id', 'movie_id'
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'rating' => $this->faker->randomNumber(9),
            'comment' => $this->faker->paragraph(1),
            'user_id' => User::factory()->create()->id,
            'movie_id' => Movie::factory()->create()->id
        ];
    }
}
