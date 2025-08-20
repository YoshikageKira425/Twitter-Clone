<?php

namespace App\Filament\Admin\Resources\Tweets\Schemas;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class TweetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make("user_id")
                    ->label("User")
                    ->options(User::query()->pluck('name', 'id'))
                    ->required()
                    ->placeholder("Select a user"),
                
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->maxSize(1024) // 1MB
                    ->acceptedFileTypes(['image/*']),
                
                TextInput::make('content')
                    ->label('Content')
                    ->maxLength(280),
            ]);
    }
}
