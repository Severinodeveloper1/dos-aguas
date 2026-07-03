<?php

namespace App\Filament\Pages;

use App\Models\CompanyInfo;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManageCompanyInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Historia y Legado';
    protected static ?string $title = 'Gestionar Historia y Legado';
    protected static string|\UnitEnum|null $navigationGroup = 'Gestión de Contenido';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-company-info';

    public ?array $data = [];

    public function mount(): void
    {
        $companyInfo = CompanyInfo::firstOrNew();
        $this->form->fill($companyInfo->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('mission')
                    ->label('Misión')
                    ->required(),
                RichEditor::make('vision')
                    ->label('Visión')
                    ->required(),
                RichEditor::make('short_history')
                    ->label('Historia Corta (Nosotros)')
                    ->required(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar Cambios')
                ->submit('save')
                ->color('primary'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $companyInfo = CompanyInfo::firstOrNew();
        $companyInfo->fill($data);
        $companyInfo->save();

        Notification::make()
            ->title('Configuración guardada correctamente')
            ->success()
            ->send();
    }
}
