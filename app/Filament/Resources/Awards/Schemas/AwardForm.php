<?php

namespace App\Filament\Resources\Awards\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AwardForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalles del Premio / Reconocimiento')
                    ->description('Información básica sobre el premio o galardón obtenido.')
                    ->components([
                        TextInput::make('title')
                            ->label('Título del Premio')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Medalla de Oro - Chocolate Ucayali'),
                        TextInput::make('country')
                            ->label('País / Región')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: París, Francia'),
                        DatePicker::make('date')
                            ->label('Fecha de Otorgamiento')
                            ->required(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(1000)
                            ->placeholder('Ej: Reconocimiento otorgado por su sabor excepcional y notas cítricas únicas.')
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Galería de Imágenes')
                    ->description('Fotografías asociadas al galardón (formato imagen).')
                    ->components([
                        FileUpload::make('product_image')
                            ->label('Imagen del Producto')
                            ->disk('public')
                            ->directory('awards/products')
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->helperText('Foto del producto galardonado.'),
                        FileUpload::make('medal_image')
                            ->label('Imagen de la Medalla')
                            ->disk('public')
                            ->directory('awards/medals')
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->helperText('Foto o diseño de la medalla obtenida.'),
                        FileUpload::make('certificate_image')
                            ->label('Imagen del Certificado')
                            ->disk('public')
                            ->directory('awards/certificates')
                            ->image()
                            ->maxSize(5120) // 5MB
                            ->helperText('Foto o PDF escaneado del diploma/certificado.'),
                    ])->columns(3),
            ])->columns(1);
    }
}
