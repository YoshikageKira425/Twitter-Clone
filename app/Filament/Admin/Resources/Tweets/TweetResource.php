<?php

namespace App\Filament\Admin\Resources\Tweets;

use App\Filament\Admin\Resources\Tweets\Pages\CreateTweet;
use App\Filament\Admin\Resources\Tweets\Pages\EditTweet;
use App\Filament\Admin\Resources\Tweets\Pages\ListTweets;
use App\Filament\Admin\Resources\Tweets\Schemas\TweetForm;
use App\Filament\Admin\Resources\Tweets\Tables\TweetsTable;
use App\Models\Tweet;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TweetResource extends Resource
{
    protected static ?string $model = Tweet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TweetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TweetsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTweets::route('/'),
            'create' => CreateTweet::route('/create'),
            'edit' => EditTweet::route('/{record}/edit'),
        ];
    }
}
