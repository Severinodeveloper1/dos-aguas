<?php

namespace App\Filament\Resources\TimelineEvents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TimelineEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('title_en')
                    ->default(null),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('description_en')
                    ->default(null)
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->image(),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
