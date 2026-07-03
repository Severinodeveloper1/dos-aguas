<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProductTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Imagen')
                    ->disk('public')
                    ->square()
                    ->circular()
                    ->stacked()
                    ->limit(2),
                TextColumn::make('name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('variants')
                    ->label('Presentaciones')
                    ->state(fn ($record) => $record->variants->count())
                    ->badge()
                    ->color('gray'),
                TextColumn::make('stock_total')
                    ->label('Stock Total')
                    ->state(fn ($record) => $record->variants->sum('stock'))
                    ->sortable(query: fn ($query, $direction) => $query->withSum('variants', 'stock')->orderBy('variants_sum_stock', $direction))
                    ->color(fn ($state) => $state <= 10 ? 'danger' : 'success'),
                ToggleColumn::make('is_active')
                    ->label('Activo'),
                TextColumn::make('created_at')
                    ->label('Creado En')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->relationship('category', 'name'),
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
