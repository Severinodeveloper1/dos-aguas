<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Banner')
                    ->description('Detalles del texto superpuesto sobre el banner.')
                    ->components([
                        TextInput::make('title')
                            ->label('Título Superpuesto')
                            ->maxLength(255)
                            ->placeholder('Ej: Cruce de los ríos Aguaytía y San Alejandro'),
                        TextInput::make('subtitle')
                            ->label('Subtítulo')
                            ->maxLength(255)
                            ->placeholder('Ej: Tradición, aroma y sabor amazónico'),
                        TextInput::make('button_text')
                            ->label('Texto del Botón (CTA)')
                            ->maxLength(50)
                            ->placeholder('Ej: Ver Catálogo'),
                        TextInput::make('button_url')
                            ->label('Enlace del Botón')
                            ->maxLength(255)
                            ->url()
                            ->placeholder('Ej: https://dosaguas.com/catalogo'),
                    ])->columns(2),

                Section::make('Multimedia y Orden')
                    ->description('Carga de imagen o video y visibilidad.')
                    ->components([
                        Select::make('media_type')
                            ->label('Tipo de Multimedia')
                            ->options([
                                'image' => 'Imagen',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->default('image'),
                        FileUpload::make('media_path')
                            ->label('Archivo Multimedia (Video o Imagen)')
                            ->disk('public')
                            ->directory('banners')
                            ->required()
                            ->maxSize(102400) // 100MB
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'video/mp4',
                                'video/webm',
                                'video/quicktime'
                            ])
                            ->helperText('Sube una imagen o video HD. Tamaño máximo: 100MB.'),
                        TextInput::make('order')
                            ->label('Orden de Aparición')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])->columns(2),
            ])->columns(1);
    }
}
