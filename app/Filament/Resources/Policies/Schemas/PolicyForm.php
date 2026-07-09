<?php

namespace App\Filament\Resources\Policies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles de la Política')
                    ->components([
                        TextInput::make('title')
                            ->label('Título de la Política')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Ej: Política de Privacidad'),
                        
                        TextInput::make('slug')
                            ->label('Slug / URL Amigable')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('ej: politica-de-privacidad'),

                        TextInput::make('order')
                            ->label('Orden de Visualización')
                            ->numeric()
                            ->default(0)
                            ->required(),

                        Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true),
                    ])->columns(2),

                Section::make('Contenido de la Política')
                    ->components([
                        RichEditor::make('content')
                            ->label('Cuerpo / Descripción')
                            ->required()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
