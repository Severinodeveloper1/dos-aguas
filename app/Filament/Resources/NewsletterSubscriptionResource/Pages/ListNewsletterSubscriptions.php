<?php

namespace App\Filament\Resources\NewsletterSubscriptionResource\Pages;

use App\Filament\Resources\NewsletterSubscriptionResource;
use App\Models\NewsletterSubscription;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListNewsletterSubscriptions extends ListRecords
{
    protected static string $resource = NewsletterSubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_all_csv')
                ->label('Exportar Todos a CSV')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action(function () {
                    $subscribers = NewsletterSubscription::orderBy('subscribed_at', 'desc')->get();

                    $csvData = [];
                    $csvData[] = ['Correo Electrónico', 'Estado Suscripción', 'Fecha de Suscripción'];

                    foreach ($subscribers as $item) {
                        $csvData[] = [
                            $item->email,
                            $item->is_active ? 'Activo' : 'Inactivo',
                            $item->subscribed_at ? $item->subscribed_at->format('d/m/Y H:i') : '',
                        ];
                    }

                    $filename = 'suscriptores-boletin-' . date('Y-m-d-H-i-s') . '.csv';
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
        ];
    }
}
