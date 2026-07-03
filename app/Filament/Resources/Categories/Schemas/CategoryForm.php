<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información de la Categoría')
                    ->description('Defina el nombre, slug y detalles principales de la categoría de catálogo.')
                    ->components([
                        TextInput::make('name')
                            ->label('Nombre de la Categoría')
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
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('order')
                            ->label('Orden de Clasificación')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true),
                    ])->columns(2),

                Section::make('Optimización SEO')
                    ->description('Campos para mejorar el ranking de búsqueda en Google.')
                    ->components([
                        TextInput::make('meta_title')
                            ->label('Meta Título')
                            ->maxLength(60)
                            ->placeholder('Ej: Esencia Pura - Cacao 70% y 100% | Dos Aguas')
                            ->helperText('Máximo recomendado: 60 caracteres.'),
                        Textarea::make('meta_description')
                            ->label('Meta Descripción')
                            ->maxLength(160)
                            ->placeholder('Ej: Compra barras de chocolate elaboradas con puro cacao orgánico de Ucayali. Esencia pura de sabor y aroma natural.')
                            ->helperText('Máximo recomendado: 160 caracteres.'),
                    ])->columns(1),
            ])->columns(1);
    }
}
