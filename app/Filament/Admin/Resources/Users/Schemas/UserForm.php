<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('profile_picture')
                    ->label('Profile Picture')
                    ->image()
                    ->maxSize(2048) 
                    ->required()
                    ->placeholder('Upload your profile picture'),

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter your name'),
                
                TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->placeholder('Enter your email address'),

                TextInput::make('password')
                    ->label('Password')
                    ->required()
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->placeholder('Enter your password'),
                
                Textarea::make('bio')
                    ->label('Bio')
                    ->maxLength(500)
                    ->placeholder('Tell us about yourself'),

                Toggle::make('is_admin')
                    ->label('Is Admin')
                    ->default(true)
                    ->inline()
                    ->helperText('Toggle to make the user admin or not'),
            ]);
    }
}
