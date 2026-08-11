<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Categoría y Traducciones')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('name')
                                            ->label('Nombre de la Categoría (Español)')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->placeholder('Ej: Esencia Pura'),
                                        TextInput::make('slug')
                                            ->label('Slug / URL Amigable')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->placeholder('ej: esencia-pura'),
                                    ]),
                                Textarea::make('description')
                                    ->label('Descripción (Español)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('meta_title')
                                            ->label('Meta Título (Español)')
                                            ->maxLength(60),
                                        Textarea::make('meta_description')
                                            ->label('Meta Descripción (Español)')
                                            ->maxLength(160)
                                            ->rows(2),
                                    ]),
                            ]),

                        Tab::make('Inglés (EN)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('name_en')
                                    ->label('Nombre de la Categoría (Inglés)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Pure Essence'),
                                Textarea::make('description_en')
                                    ->label('Descripción (Inglés)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('meta_title_en')
                                            ->label('Meta Título (Inglés)')
                                            ->maxLength(60),
                                        Textarea::make('meta_description_en')
                                            ->label('Meta Descripción (Inglés)')
                                            ->maxLength(160)
                                            ->rows(2),
                                    ]),
                            ]),

                        Tab::make('Alemán (DE)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('name_de')
                                    ->label('Nombre de la Categoría (Alemán)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Reine Essenz'),
                                Textarea::make('description_de')
                                    ->label('Descripción (Alemán)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('meta_title_de')
                                            ->label('Meta Título (Alemán)')
                                            ->maxLength(60),
                                        Textarea::make('meta_description_de')
                                            ->label('Meta Descripción (Alemán)')
                                            ->maxLength(160)
                                            ->rows(2),
                                    ]),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Configuración e Imagen')
                    ->description('Imagen de portada y ajustes de orden y estado.')
                    ->components([
                        FileUpload::make('photo_path')
                            ->label('Foto de la Categoría')
                            ->image()
                            ->disk('public')
                            ->directory('categories')
                            ->required(),
                        Grid::make(2)
                            ->components([
                                TextInput::make('order')
                                    ->label('Orden de Clasificación')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Activa')
                                    ->default(true),
                            ]),
                    ])->columns(2),
            ])->columns(1);
    }
}
