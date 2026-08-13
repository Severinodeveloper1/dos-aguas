<?php

namespace App\Filament\Resources\Policies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PolicyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Configuración General')
                    ->components([
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
                    ])->columnSpanFull(),

                Tabs::make('Traducciones de la Política')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título de la Política (ES)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->placeholder('Ej: Política de Privacidad'),

                                RichEditor::make('content')
                                    ->label('Contenido / Descripción (ES)')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('English (EN)')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                TextInput::make('title_en')
                                    ->label('Policy Title (EN)')
                                    ->maxLength(255)
                                    ->placeholder('e.g., Privacy Policy'),

                                RichEditor::make('content_en')
                                    ->label('Content / Details (EN)')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Deutsch (DE)')
                            ->icon('heroicon-o-globe-americas')
                            ->schema([
                                TextInput::make('title_de')
                                    ->label('Richtlinientitel (DE)')
                                    ->maxLength(255)
                                    ->placeholder('z.B., Datenschutzrichtlinie'),

                                RichEditor::make('content_de')
                                    ->label('Inhalt / Details (DE)')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }
}
