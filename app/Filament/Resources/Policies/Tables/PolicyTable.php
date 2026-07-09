<?php

namespace App\Filament\Resources\Policies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PolicyTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')
            ->columns([
                TextColumn::make('title')
                    ->label('Título de la Política')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug / URL')
                    ->searchable(),
                TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Activa'),
                TextColumn::make('created_at')
                    ->label('Creado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
