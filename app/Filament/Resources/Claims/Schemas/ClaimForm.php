<?php

namespace App\Filament\Resources\Claims\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClaimForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identificación de la Reclamación')
                    ->components([
                        TextInput::make('claim_code')
                            ->label('Código de Reclamo')
                            ->disabled(),
                        TextInput::make('type')
                            ->label('Tipo')
                            ->disabled()
                            ->formatStateUsing(fn ($state) => $state === 'reclamacion' ? 'RECLAMACIÓN' : 'QUEJA'),
                        TextInput::make('created_at')
                            ->label('Fecha de Registro')
                            ->disabled(),
                    ])->columns(3),

                Section::make('Información del Consumidor')
                    ->components([
                        TextInput::make('full_name')
                            ->label('Nombre Completo')
                            ->disabled(),
                        TextInput::make('document_type')
                            ->label('Tipo de Documento')
                            ->disabled(),
                        TextInput::make('document_number')
                            ->label('Número de Documento')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->disabled(),
                        TextInput::make('address')
                            ->label('Dirección')
                            ->disabled()
                            ->columnSpan(2),
                        Toggle::make('is_minor')
                            ->label('Menor de Edad')
                            ->disabled(),
                    ])->columns(3),

                Section::make('Información del Apoderado (Tutor)')
                    ->description('Solo aplica si el reclamante es menor de edad.')
                    ->components([
                        TextInput::make('representative_name')
                            ->label('Nombre del Apoderado')
                            ->disabled()
                            ->placeholder('No aplica'),
                        TextInput::make('representative_document_type')
                            ->label('Tipo de Documento')
                            ->disabled()
                            ->placeholder('No aplica'),
                        TextInput::make('representative_document_number')
                            ->label('Número de Documento')
                            ->disabled()
                            ->placeholder('No aplica'),
                    ])->columns(3),

                Section::make('Detalle del Bien y Reclamación')
                    ->components([
                        TextInput::make('claimed_amount')
                            ->label('Monto Reclamado (S/.)')
                            ->numeric()
                            ->disabled()
                            ->placeholder('No especificado'),
                        Textarea::make('product_service_description')
                            ->label('Descripción del Bien o Servicio')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(3),
                        Textarea::make('claim_details')
                            ->label('Detalle de la Disconformidad (Queja/Reclamo)')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(4),
                        Textarea::make('consumer_request')
                            ->label('Pedido Concreto del Consumidor')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(1),

                Section::make('Resolución del Reclamo (Plazo legal 15 días hábiles)')
                    ->components([
                        Select::make('status')
                            ->label('Estado de Gestión')
                            ->options([
                                'pending' => 'Pendiente',
                                'in_process' => 'En Proceso',
                                'resolved' => 'Resuelto',
                            ])
                            ->required()
                            ->default('pending')
                            ->reactive(),
                        Textarea::make('resolution_response')
                            ->label('Respuesta Resolutiva / Resolución')
                            ->placeholder('Redacta aquí la respuesta formal que se enviará al correo del consumidor...')
                            ->rows(6)
                            ->required(),
                        DateTimePicker::make('resolved_at')
                            ->label('Fecha de Resolución')
                            ->disabled()
                            ->placeholder('Se asigna al guardar como Resuelto'),
                    ])->columns(1),
            ])->columns(1);
    }
}
