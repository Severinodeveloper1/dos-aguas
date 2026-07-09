<?php

namespace App\Filament\Resources\Claims\Pages;

use App\Filament\Resources\Claims\ClaimResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditClaim extends EditRecord
{
    protected static string $resource = ClaimResource::class;

    protected function beforeSave(): void
    {
        if ($this->data['status'] === 'resolved') {
            if (!$this->record->resolved_at) {
                $this->data['resolved_at'] = now();
            }
        } else {
            $this->data['resolved_at'] = null;
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
