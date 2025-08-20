<?php

namespace App\Filament\Admin\Resources\Tweets\Pages;

use App\Filament\Admin\Resources\Tweets\TweetResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTweet extends CreateRecord
{
    protected static string $resource = TweetResource::class;
}
