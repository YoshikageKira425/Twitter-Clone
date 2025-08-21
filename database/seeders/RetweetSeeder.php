<?php

namespace Database\Seeders;

use App\Models\Retweet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RetweetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Retweet::factory(20)->create([
            'retweetable_type' => 'App\Models\Tweet',
        ]);;
        Retweet::factory(20)->create([
            'retweetable_type' => 'App\Models\Comment',
        ]);;
    }
}
