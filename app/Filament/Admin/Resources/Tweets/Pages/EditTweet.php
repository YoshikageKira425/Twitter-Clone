<?php

namespace App\Filament\Admin\Resources\Tweets\Pages;

use App\Filament\Admin\Resources\Tweets\TweetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTweet extends EditRecord
{
    protected static string $resource = TweetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
