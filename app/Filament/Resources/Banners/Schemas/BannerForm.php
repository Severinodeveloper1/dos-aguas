<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Textos del Banner y Traducciones')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title')
                                            ->label('Título Superpuesto (Español)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Cruce de los ríos Aguaytía y San Alejandro'),
                                        TextInput::make('subtitle')
                                            ->label('Subtítulo (Español)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Tradición, aroma y sabor amazónico'),
                                    ]),
                                TextInput::make('button_text')
                                    ->label('Texto del Botón CTA (Español)')
                                    ->maxLength(50)
                                    ->placeholder('Ej: Ver Catálogo'),
                            ]),

                        Tab::make('Inglés (EN)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title_en')
                                            ->label('Título Superpuesto (Inglés)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Crossing of the Aguaytía and San Alejandro Rivers'),
                                        TextInput::make('subtitle_en')
                                            ->label('Subtítulo (Inglés)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Amazonian tradition, aroma and flavor'),
                                    ]),
                                TextInput::make('button_text_en')
                                    ->label('Texto del Botón CTA (Inglés)')
                                    ->maxLength(50)
                                    ->placeholder('Ej: View Catalog'),
                            ]),

                        Tab::make('Alemán (DE)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title_de')
                                            ->label('Título Superpuesto (Alemán)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Kreuzung der Flüsse Aguaytía und San Alejandro'),
                                        TextInput::make('subtitle_de')
                                            ->label('Subtítulo (Alemán)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Amazonische Tradition, Aroma und Geschmack'),
                                    ]),
                                TextInput::make('button_text_de')
                                    ->label('Texto del Botón CTA (Alemán)')
                                    ->maxLength(50)
                                    ->placeholder('Ej: Katalog ansehen'),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Enlace y Multimedia')
                    ->description('Carga de imagen o video y visibilidad para web y dispositivos móviles.')
                    ->components([
                        TextInput::make('button_url')
                            ->label('Enlace del Botón (URL)')
                            ->maxLength(255)
                            ->url()
                            ->placeholder('Ej: https://dosaguas.com/catalogo')
                            ->columnSpanFull(),

                        Select::make('media_type')
                            ->label('Tipo de Multimedia (Web/Escritorio)')
                            ->options([
                                'image' => 'Imagen',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->default('image'),
                        FileUpload::make('media_path')
                            ->label('Archivo Multimedia Web (Video o Imagen)')
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
                            ->helperText('Sube una imagen o video HD para escritorio. Tamaño máximo: 100MB.'),
                        
                        Select::make('mobile_media_type')
                            ->label('Tipo de Multimedia (Móvil)')
                            ->options([
                                'image' => 'Imagen',
                                'video' => 'Video',
                            ])
                            ->required()
                            ->default('image'),
                        FileUpload::make('mobile_media_path')
                            ->label('Archivo Multimedia Móvil (Opcional - Fallback a Web)')
                            ->disk('public')
                            ->directory('banners')
                            ->maxSize(51200) // 50MB
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'video/mp4',
                                'video/webm',
                                'video/quicktime'
                            ])
                            ->helperText('Sube una versión vertical o ligera para dispositivos móviles (Opcional). Tamaño máximo: 50MB.'),

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
