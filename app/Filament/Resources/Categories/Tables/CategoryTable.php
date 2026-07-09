<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CategoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nombre Categoría')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label('Slug / URL'),
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
