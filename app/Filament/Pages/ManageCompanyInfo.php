<?php

namespace App\Filament\Pages;

use App\Models\CompanyInfo;
use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ManageCompanyInfo extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationLabel = 'Configuración General';
    protected static ?string $title = 'Gestionar Datos de la Empresa';
    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';
    protected static ?int $navigationSort = 1;

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
                Tabs::make('Configuración')
                    ->tabs([
                        Tab::make('General y Contacto')
                            ->components([
                                TextInput::make('name')
                                    ->label('Nombre de la Empresa')
                                    ->required(),
                                FileUpload::make('logo_path')
                                    ->label('Logo de la Empresa')
                                    ->image()
                                    ->disk('public')
                                    ->directory('company')
                                    ->columnSpanFull(),
                                TextInput::make('phone')
                                    ->label('Teléfono de Contacto'),
                                TextInput::make('whatsapp_phone')
                                    ->label('WhatsApp de la Empresa'),
                                TextInput::make('email')
                                    ->label('Email de Contacto')
                                    ->email(),
                                TextInput::make('contact_email_receiver')
                                    ->label('Email que recibe alertas')
                                    ->email()
                                    ->helperText('A este correo llegarán las alertas de reclamos y contacto. Si se deja vacío, se usará el Email de Contacto por defecto.'),
                                TextInput::make('address')
                                    ->label('Dirección Física')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Tab::make('Redes Sociales')
                            ->components([
                                TextInput::make('facebook_url')
                                    ->label('Facebook URL')
                                    ->url(),
                                TextInput::make('instagram_url')
                                    ->label('Instagram URL')
                                    ->url(),
                                TextInput::make('tiktok_url')
                                    ->label('TikTok URL')
                                    ->url(),
                                TextInput::make('youtube_url')
                                    ->label('YouTube URL')
                                    ->url(),
                            ])->columns(2),

                        Tab::make('Nosotros y Legado')
                            ->components([
                                RichEditor::make('about_history')
                                    ->label('Historia de la Empresa'),
                                RichEditor::make('about_mission')
                                    ->label('Misión de la Empresa'),
                                RichEditor::make('about_vision')
                                    ->label('Visión de la Empresa'),
                                RichEditor::make('about_values')
                                    ->label('Valores de la Empresa'),
                                FileUpload::make('brochure_path')
                                    ->label('Brochure Corporativo (PDF)')
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->disk('public')
                                    ->directory('company')
                                    ->maxSize(102400)
                                    ->helperText('Sube el brochure corporativo en formato PDF. Tamaño máximo: 100MB.')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Tab::make('Localización')
                            ->components([
                                Textarea::make('maps_iframe')
                                    ->label('Iframe de Google Maps')
                                    ->placeholder('<iframe src="..." ...></iframe>')
                                    ->rows(6)
                                    ->columnSpanFull()
                                    ->helperText('Pega el código HTML del mapa embebido de Google Maps aquí.'),
                            ]),

                        Tab::make('Galería')
                            ->components([
                                FileUpload::make('gallery_photos')
                                    ->label('Imágenes del Catálogo / Galería')
                                    ->multiple()
                                    ->image()
                                    ->disk('public')
                                    ->directory('gallery')
                                    ->columnSpanFull()
                                    ->helperText('Puedes subir múltiples imágenes para la galería pública de la Hacienda. Tamaño máximo por imagen: 100MB.'),
                            ]),
                    ])
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

        // Map the new fields to keep old fields synced just in case
        $data['mission'] = $data['about_mission'];
        $data['vision'] = $data['about_vision'];
        $data['short_history'] = $data['about_history'];

        $companyInfo->fill($data);
        $companyInfo->save();

        Notification::make()
            ->title('Configuración guardada correctamente')
            ->success()
            ->send();
    }
}
