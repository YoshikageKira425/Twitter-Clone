<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 5),
            'likeable_id' => fake()->numberBetween(1, 20),
            'likeable_type' => 'App\Models\Tweet',
        ];
    }

    public function forTweet()
    {
        return $this->state(fn() => [
            'likeable_type' => '\App\Models\Tweet::class',
        ]);
    }

    public function forPost()
    {
        return $this->state(fn() => [
            'likeable_type' => '\App\Models\Post::class',
        ]);
    }
}
