<?php

namespace App\Filament\Resources\Awards\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class AwardTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                ImageColumn::make('product_image')
                    ->label('Producto')
                    ->disk('public')
                    ->square(),
                ImageColumn::make('medal_image')
                    ->label('Medalla')
                    ->disk('public')
                    ->square(),
                ImageColumn::make('certificate_image')
                    ->label('Certificado')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('country')
                    ->label('País / Región')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
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
