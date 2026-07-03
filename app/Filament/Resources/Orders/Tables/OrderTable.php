<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Table;

class OrderTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('order_number')
                    ->label('Pedido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Envío')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'preparing' => 'warning',
                        'shipped' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'preparing' => 'Preparando',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    }),
                TextColumn::make('payment_status')
                    ->label('Pago')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                        default => $state,
                    }),
                TextColumn::make('payment_method')
                    ->label('Método')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('total')
                    ->label('Total')
                    ->numeric()
                    ->prefix('S/.')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado Envío')
                    ->options([
                        'pending' => 'Pendiente',
                        'preparing' => 'En Preparación',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado',
                    ]),
                SelectFilter::make('payment_status')
                    ->label('Estado Pago')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'failed' => 'Fallido',
                        'refunded' => 'Reembolsado',
                    ]),
                Filter::make('created_at')
                    ->label('Fecha del Pedido')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Desde'),
                        DatePicker::make('created_until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_sales')
                        ->label('Exportar Ventas (CSV)')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csvData = [];
                            $csvData[] = ['Pedido', 'Cliente', 'Email', 'Celular', 'Estado Envío', 'Subtotal', 'Envío', 'Total', 'Método Pago', 'Estado Pago', 'Fecha'];

                            foreach ($records as $record) {
                                $csvData[] = [
                                    $record->order_number,
                                    $record->customer_name,
                                    $record->customer_email,
                                    $record->customer_phone,
                                    $record->status,
                                    $record->subtotal,
                                    $record->shipping_cost,
                                    $record->total,
                                    $record->payment_method,
                                    $record->payment_status,
                                    $record->created_at->format('d/m/Y H:i'),
                                ];
                            }

                            $filename = 'ventas-' . date('Y-m-d-H-i-s') . '.csv';
                            $tempPath = tempnam(sys_get_temp_dir(), 'csv');
                            $file = fopen($tempPath, 'w');

                            // UTF-8 BOM
                            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                            foreach ($csvData as $row) {
                                fputcsv($file, $row);
                            }
                            fclose($file);

                            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
                        })
                        ->icon('heroicon-o-document-arrow-down'),
                ]),
            ]);
    }
}
