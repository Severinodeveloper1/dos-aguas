<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->description('Detalles principales del centro de acopio o planta.')
                    ->components([
                        TextInput::make('name')
                            ->label('Nombre de la Sede')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Planta Chorrillos'),
                        Select::make('type')
                            ->label('Tipo de Sede')
                            ->options([
                                'acopio' => 'Centro de Acopio',
                                'planta' => 'Planta de Procesamiento',
                                'oficina' => 'Oficina Administrativa',
                            ])
                            ->required(),
                        TextInput::make('address')
                            ->label('Dirección')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Av. Defensores del Morro 1234, Chorrillos'),
                        TextInput::make('phone')
                            ->label('Teléfono de Contacto')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('hours')
                            ->label('Horario de Atención')
                            ->maxLength(255)
                            ->placeholder('Ej: Lun - Sab 8:00 AM - 6:00 PM'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ])->columns(2),

                Section::make('Mapas Interactivos')
                    ->description('Administre los marcos (iframes) de Google Maps para esta sede.')
                    ->components([
                        Repeater::make('map_frames')
                            ->label('Marcos de Mapa (HTML Embed)')
                            ->components([
                                Textarea::make('iframe_code')
                                    ->label('Código HTML del Iframe')
                                    ->rows(4)
                                    ->required()
                                    ->placeholder('Pegue aquí el código <iframe src="..." ...></iframe> desde Google Maps')
                                    ->helperText('Vaya a Google Maps, busque la ubicación, haga clic en "Compartir", seleccione "Incorporar un mapa" y copie el código HTML.'),
                            ])
                            ->itemLabel(fn(array $state): ?string => $state['iframe_code'] ?? 'Mapa Iframe')
                            ->collapsible()
                            ->cloneable()
                            ->minItems(0)
                            ->default([]),
                    ]),
            ])->columns(1);
    }
}
