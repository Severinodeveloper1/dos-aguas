<?php

namespace App\Filament\Resources\TimelineEvents\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class TimelineEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Hito Histórico y Traducciones')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('title')
                                    ->label('Título del Hito (Español)')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Fundación de Hacienda Dos Aguas'),
                                Textarea::make('description')
                                    ->label('Descripción (Español)')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Inglés (EN)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('title_en')
                                    ->label('Título del Hito (Inglés)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Foundation of Hacienda Dos Aguas'),
                                Textarea::make('description_en')
                                    ->label('Descripción (Inglés)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Alemán (DE)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('title_de')
                                    ->label('Título del Hito (Alemán)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Gründung der Hacienda Dos Aguas'),
                                Textarea::make('description_de')
                                    ->label('Descripción (Alemán)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Año, Imagen y Estado')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('year')
                                    ->label('Año o Período')
                                    ->required()
                                    ->placeholder('Ej: 2018'),
                                TextInput::make('order')
                                    ->label('Orden de Aparición')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ]),
                        FileUpload::make('image_path')
                            ->label('Imagen del Hito')
                            ->disk('public')
                            ->directory('timeline_events')
                            ->image()
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
            ])->columns(1);
    }
}
