<?php

namespace App\Filament\Admin\Resources\Tweets\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TweetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User Name')
                    ->sortable()
                    ->searchable(),

                ImageColumn::make('image')
                    ->label('Image')
                    ->imageSize(40),

                TextColumn::make('content')
                    ->label('Content')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('likes_count')
                    ->counts('likes')
                    ->label('Likes')
                    ->sortable(),

                TextColumn::make('retweets_count')
                    ->counts('retweets')
                    ->label('Retweets')
                    ->sortable(),

                TextColumn::make('comments_count')
                    ->counts('comments')
                    ->label('Comments')
                    ->sortable(),

                TextColumn::make('bookmarks_count')
                    ->counts('bookmarks')
                    ->label('Bookmarks')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label("User")
                    ->options(function () {return User::pluck('name', 'id'); }),

                QueryBuilder::make()
                    ->constraints([
                        NumberConstraint::make("likes_count"),
                        NumberConstraint::make("retweets_count"),
                        NumberConstraint::make("comments_count"),
                        NumberConstraint::make("bookmarks_count"),
                    ])
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
