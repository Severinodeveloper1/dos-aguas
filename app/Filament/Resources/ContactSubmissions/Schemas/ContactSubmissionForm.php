<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Remitente')
                    ->components([
                        TextInput::make('name')
                            ->label('Nombre Completo')
                            ->disabled(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->disabled(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->disabled()
                            ->placeholder('No especificado'),
                        TextInput::make('subject')
                            ->label('Asunto')
                            ->disabled()
                            ->columnSpanFull(),
                        Textarea::make('message')
                            ->label('Mensaje')
                            ->disabled()
                            ->columnSpanFull()
                            ->rows(5),
                    ])->columns(3),

                Section::make('Seguimiento Administrativo')
                    ->components([
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'read' => 'Leído',
                                'answered' => 'Respondido',
                            ])
                            ->required()
                            ->default('pending'),
                        Textarea::make('admin_notes')
                            ->label('Notas del Administrador')
                            ->placeholder('Registra notas de seguimiento aquí...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(1),
            ])->columns(1);
    }
}
