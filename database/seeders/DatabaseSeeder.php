<?php

namespace Database\Seeders;

use App\Models\User;
use Dom\Comment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TweetSeeder::class,
            CommentSeeder::class,
            LikeSeeder::class,
            RetweetSeeder::class,
            FollowSeeder::class,
        ]);
    }
}
