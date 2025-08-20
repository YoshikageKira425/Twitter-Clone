<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
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
            'content' => fake()->sentence(),
            'commentable_id' => fake()->numberBetween(1, 20),
            'commentable_type' => 'App\Models\Tweet',
        ];
    }

    public function forTweet()
    {
        return $this->state(fn() => [
            'commentable_type' => '\App\Models\Tweet::class',
        ]);
    }

    public function forPost()
    {
        return $this->state(fn() => [
            'commentable_type' => '\App\Models\Post::class',
        ]);
    }
}
