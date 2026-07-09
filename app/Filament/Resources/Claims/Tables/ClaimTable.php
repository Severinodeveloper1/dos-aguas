<?php

namespace App\Filament\Resources\Claims\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;

class ClaimTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('claim_code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_name')
                    ->label('Consumidor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'reclamacion' => 'danger',
                        'queja' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state === 'reclamacion' ? 'Reclamación' : 'Queja'),
                SelectColumn::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'in_process' => 'En Proceso',
                        'resolved' => 'Resuelto',
                    ])
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha Registro')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('resolved_at')
                    ->label('Fecha Resolución')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('Pendiente')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
