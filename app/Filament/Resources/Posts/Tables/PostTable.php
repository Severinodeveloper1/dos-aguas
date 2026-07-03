<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PostTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Portada')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Título del Post')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('author.name')
                    ->label('Autor')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Fecha Publicación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Publicado'),
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
