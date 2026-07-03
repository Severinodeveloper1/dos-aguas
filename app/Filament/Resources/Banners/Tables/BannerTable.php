<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class BannerTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')
            ->columns([
                ImageColumn::make('media_path')
                    ->label('Vista Previa')
                    ->disk('public')
                    ->square()
                    ->placeholder('Video o sin imagen'),
                TextColumn::make('title')
                    ->label('Título Superpuesto')
                    ->searchable()
                    ->placeholder('Sin título'),
                TextColumn::make('media_type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'video' => 'warning',
                        'image' => 'success',
                        default => 'gray',
                    }),
                ToggleColumn::make('is_active')
                    ->label('Activo'),
                TextColumn::make('order')
                    ->label('Orden')
                    ->sortable(),
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
