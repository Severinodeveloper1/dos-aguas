<?php

namespace App\Filament\Resources\Awards\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class AwardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Premio y Traducciones')
                    ->tabs([
                        Tab::make('Español (ES)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title')
                                            ->label('Título del Premio (Español)')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Ej: Medalla de Oro - Chocolate Ucayali'),
                                        TextInput::make('country')
                                            ->label('País / Región (Español)')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('Ej: París, Francia'),
                                    ]),
                                Textarea::make('description')
                                    ->label('Descripción (Español)')
                                    ->maxLength(1000)
                                    ->placeholder('Ej: Reconocimiento otorgado por su sabor excepcional y notas cítricas únicas.')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Inglés (EN)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title_en')
                                            ->label('Título del Premio (Inglés)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Gold Medal - Ucayali Chocolate'),
                                        TextInput::make('country_en')
                                            ->label('País / Región (Inglés)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Paris, France'),
                                    ]),
                                Textarea::make('description_en')
                                    ->label('Descripción (Inglés)')
                                    ->maxLength(1000)
                                    ->placeholder('Ej: Recognition granted for its exceptional flavor and unique citrus notes.')
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Alemán (DE)')
                            ->icon('heroicon-o-language')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('title_de')
                                            ->label('Título del Premio (Alemán)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Goldmedaille - Ucayali Schokolade'),
                                        TextInput::make('country_de')
                                            ->label('País / Región (Alemán)')
                                            ->maxLength(255)
                                            ->placeholder('Ej: Paris, Frankreich'),
                                    ]),
                                Textarea::make('description_de')
                                    ->label('Descripción (Alemán)')
                                    ->maxLength(1000)
                                    ->placeholder('Ej: Auszeichnung für außergewöhnlichen Geschmack und einzigartige Zitrusnoten.')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),

                Section::make('Detalles Generales')
                    ->components([
                        DatePicker::make('date')
                            ->label('Fecha de Otorgamiento')
                            ->required(),
                    ]),

                Section::make('Galería de Imágenes')
                    ->description('Fotografías asociadas al galardón (formato imagen).')
                    ->components([
                        FileUpload::make('product_image')
                            ->label('Imagen del Producto')
                            ->disk('public')
                            ->directory('awards/products')
                            ->image()
                            ->maxSize(51200) // 50MB
                            ->helperText('Foto del producto galardonado.'),
                        FileUpload::make('medal_image')
                            ->label('Imagen de la Medalla')
                            ->disk('public')
                            ->directory('awards/medals')
                            ->image()
                            ->maxSize(51200) // 50MB
                            ->helperText('Foto o diseño de la medalla obtenida.'),
                        FileUpload::make('certificate_image')
                            ->label('Imagen del Certificado')
                            ->disk('public')
                            ->directory('awards/certificates')
                            ->image()
                            ->maxSize(51200) // 50MB
                            ->helperText('Foto o PDF escaneado del diploma/certificado.'),
                    ])->columns(3),
            ])->columns(1);
    }
}
