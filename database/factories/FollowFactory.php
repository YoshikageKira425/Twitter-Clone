<?php

namespace Database\Factories;

use App\Models\Follow;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        do {
            $follower = fake()->numberBetween(1, 6);
            $following = fake()->numberBetween(1, 6);
        } while (
            $follower === $following ||
            Follow::where('follower_id', $follower)
            ->where('following_id', $following)
            ->exists()
        );

        return [
            'follower_id' => $follower,
            'following_id' => $following,
        ];
    }
}
