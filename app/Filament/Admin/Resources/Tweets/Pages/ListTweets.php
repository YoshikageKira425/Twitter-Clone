<?php

namespace App\Filament\Admin\Resources\Tweets\Pages;

use App\Filament\Admin\Resources\Tweets\TweetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTweets extends ListRecords
{
    protected static string $resource = TweetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
