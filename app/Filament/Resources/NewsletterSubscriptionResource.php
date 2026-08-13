<?php

namespace App\Filament\Resources;

use App\Models\NewsletterSubscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;

class NewsletterSubscriptionResource extends Resource
{
    protected static ?string $model = NewsletterSubscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    protected static string|\UnitEnum|null $navigationGroup = 'Atención al Cliente';

    protected static ?string $navigationLabel = 'Suscriptores de Boletín';

    protected static ?string $pluralModelLabel = 'Suscriptores de Boletín';

    protected static ?string $modelLabel = 'Suscriptor';

    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Correo copiado al portapapeles'),

                ToggleColumn::make('is_active')
                    ->label('Suscripción Activa')
                    ->sortable(),

                TextColumn::make('subscribed_at')
                    ->label('Fecha de Suscripción')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('subscribed_at', 'desc')
            ->filters([
                SelectFilter::make('is_active')
                    ->label('Estado')
                    ->options([
                        '1' => 'Activos',
                        '0' => 'Inactivos',
                    ]),
            ])
            ->actions([
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('export_selected_csv')
                        ->label('Exportar Seleccionados (CSV)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (Collection $records) {
                            $csvData = [];
                            $csvData[] = ['Correo Electrónico', 'Estado Suscripción', 'Fecha de Suscripción'];

                            foreach ($records as $record) {
                                $csvData[] = [
                                    $record->email,
                                    $record->is_active ? 'Activo' : 'Inactivo',
                                    $record->subscribed_at ? $record->subscribed_at->format('d/m/Y H:i') : '',
                                ];
                            }

                            $filename = 'suscriptores-seleccionados-' . date('Y-m-d-H-i-s') . '.csv';
                            $tempPath = tempnam(sys_get_temp_dir(), 'csv');
                            $file = fopen($tempPath, 'w');

                            // UTF-8 BOM for Excel compatibility
                            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                            foreach ($csvData as $row) {
                                fputcsv($file, $row);
                            }
                            fclose($file);

                            return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\NewsletterSubscriptionResource\Pages\ListNewsletterSubscriptions::route('/'),
        ];
    }
}
