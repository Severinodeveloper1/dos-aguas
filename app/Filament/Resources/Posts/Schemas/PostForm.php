<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->components([
                        TextInput::make('slug')
                            ->label('Slug / URL Amigable')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('ej: el-proceso-bean-to-bar-en-ucayali'),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->default(now()),
                    ]),

                Tabs::make('Contenido del Post y Traducciones')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->components([
                                TextInput::make('title')
                                    ->label('Título del Artículo (Español)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                    ->placeholder('Ej: El Proceso Bean to Bar en Ucayali'),
                                Textarea::make('excerpt')
                                    ->label('Resumen / Extracto (Español)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('content')
                                    ->label('Contenido Completo (Español)')
                                    ->required()
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
                                TextInput::make('title_en')
                                    ->label('Título del Artículo (Inglés)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: The Bean to Bar Process in Ucayali'),
                                Textarea::make('excerpt_en')
                                    ->label('Resumen / Extracto (Inglés)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('content_en')
                                    ->label('Contenido Completo (Inglés)')
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
                                TextInput::make('title_de')
                                    ->label('Título del Artículo (Alemán)')
                                    ->maxLength(255)
                                    ->placeholder('Ej: Der Bean to Bar Prozess in Ucayali'),
                                Textarea::make('excerpt_de')
                                    ->label('Resumen / Extracto (Alemán)')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                RichEditor::make('content_de')
                                    ->label('Contenido Completo (Alemán)')
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

                Section::make('Publicación y Multimedia')
                    ->components([
                        FileUpload::make('image_path')
                            ->label('Imagen de Portada (Foto destacada)')
                            ->disk('public')
                            ->directory('posts')
                            ->image()
                            ->imageEditor()
                            ->helperText('Sube una imagen de alta resolución para la portada del post.'),
                        Toggle::make('is_active')
                            ->label('Publicado')
                            ->default(true),
                        Hidden::make('author_id')
                            ->default(fn() => auth()->id()),
                    ])->columns(2),
            ])->columns(1);
    }
}
