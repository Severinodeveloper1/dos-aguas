<?php

namespace App\Filament\Pages;

use App\Models\PaymentSetting;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class ManagePaymentSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Ajustes de Pago';
    protected static ?string $title = 'Configuración de Pasarela y Pagos';
    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';
    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-payment-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = PaymentSetting::firstOrNew();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Métodos de Pago Temporales')
                    ->description('Habilita métodos alternativos mientras se aprueba la pasarela.')
                    ->components([
                        Toggle::make('cod_enabled')
                            ->label('Pago contra entrega (COD)')
                            ->default(false),
                        Toggle::make('bank_transfer_enabled')
                            ->label('Transferencia bancaria')
                            ->default(false)
                            ->live(),
                        RichEditor::make('bank_transfer_details')
                            ->label('Cuentas Bancarias y Detalles')
                            ->visible(fn ($get) => $get('bank_transfer_enabled'))
                            ->required(fn ($get) => $get('bank_transfer_enabled')),
                    ]),

                Section::make('Pasarela de Pagos (Fase 2)')
                    ->description('Parámetros de conexión para la pasarela de pagos.')
                    ->components([
                        Toggle::make('gateway_enabled')
                            ->label('Habilitar Pasarela de Pagos')
                            ->default(false)
                            ->live(),
                        Select::make('gateway_provider')
                            ->label('Proveedor de Pasarela')
                            ->options([
                                'mercadopago' => 'Mercado Pago',
                                'culqi' => 'Culqi',
                                'niubiz' => 'Niubiz',
                            ])
                            ->visible(fn ($get) => $get('gateway_enabled'))
                            ->required(fn ($get) => $get('gateway_enabled')),
                        TextInput::make('gateway_public_key')
                            ->label('Llave Pública (Public Key)')
                            ->visible(fn ($get) => $get('gateway_enabled'))
                            ->required(fn ($get) => $get('gateway_enabled')),
                        TextInput::make('gateway_private_key')
                            ->label('Llave Privada / Token (Private/Secret Key)')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->visible(fn ($get) => $get('gateway_enabled'))
                            ->required(fn ($get) => $get('gateway_enabled')),
                        Toggle::make('gateway_sandbox_mode')
                            ->label('Modo Sandbox (Pruebas)')
                            ->default(true)
                            ->visible(fn ($get) => $get('gateway_enabled')),
                    ]),
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
        $settings = PaymentSetting::firstOrNew();
        $settings->fill($data);
        $settings->save();

        Notification::make()
            ->title('Ajustes de pago guardados correctamente')
            ->success()
            ->send();
    }
}
