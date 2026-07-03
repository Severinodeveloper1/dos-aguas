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
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Artículo')
                    ->description('Complete los campos para publicar en el blog "Desde la Semilla".')
                    ->components([
                        TextInput::make('title')
                            ->label('Título del Artículo')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                            ->placeholder('Ej: El Proceso Bean to Bar en Ucayali'),
                        TextInput::make('slug')
                            ->label('Slug / URL Amigable')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('ej: el-proceso-bean-to-bar-en-ucayali'),
                        Textarea::make('excerpt')
                            ->label('Resumen / Extracto')
                            ->rows(3)
                            ->helperText('Una breve descripción que aparecerá en el listado del blog.')
                            ->columnSpanFull(),
                        RichEditor::make('content')
                            ->label('Contenido Completo')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Publicación y Multimedia')
                    ->components([
                        FileUpload::make('image_path')
                            ->label('Imagen de Portada (Foto destacada)')
                            ->disk('public')
                            ->directory('posts')
                            ->image()
                            ->imageEditor()
                            ->helperText('Sube una imagen de alta resolución para la portada del post.'),
                        DateTimePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->default(now()),
                        Toggle::make('is_active')
                            ->label('Publicado')
                            ->default(true),
                        Hidden::make('author_id')
                            ->default(fn() => auth()->id()),
                    ])->columns(2),

                Section::make('Optimización SEO')
                    ->description('Configura los campos meta de Google.')
                    ->components([
                        TextInput::make('meta_title')
                            ->label('Meta Título')
                            ->maxLength(60),
                        Textarea::make('meta_description')
                            ->label('Meta Descripción')
                            ->maxLength(160)
                            ->rows(3),
                    ])->collapsible(),
            ])->columns(1);
    }
}
