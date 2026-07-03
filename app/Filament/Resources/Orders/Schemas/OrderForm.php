<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\ProductVariant;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Detalle del Pedido')
                    ->tabs([
                        Tab::make('Información del Cliente')
                            ->icon('heroicon-o-user')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('order_number')
                                            ->label('Código de Pedido')
                                            ->required()
                                            ->readonly()
                                            ->default(fn() => 'DA-' . date('Ymd') . '-' . strtoupper(Str::random(5))),
                                        TextInput::make('customer_name')
                                            ->label('Nombre Completo')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('customer_email')
                                            ->label('Correo Electrónico')
                                            ->email()
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('customer_phone')
                                            ->label('Teléfono')
                                            ->tel()
                                            ->maxLength(50),
                                    ]),
                                Textarea::make('shipping_address')
                                    ->label('Dirección de Envío')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Textarea::make('billing_address')
                                    ->label('Dirección de Facturación (Opcional)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Textarea::make('notes')
                                    ->label('Notas del Pedido')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Detalle de Productos y Pago')
                            ->icon('heroicon-o-shopping-bag')
                            ->components([
                                // 1. Financial Totals Section at the top
                                Section::make('Resumen de Montos')
                                    ->components([
                                        Grid::make(4)
                                            ->components([
                                                TextInput::make('subtotal')
                                                    ->label('Subtotal (Sin Impuesto)')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->readonly()
                                                    ->default(0.00),
                                                TextInput::make('tax')
                                                    ->label('Impuesto (IGV 18%)')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->readonly()
                                                    ->default(0.00),
                                                TextInput::make('shipping_cost')
                                                    ->label('Costo de Envío')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->default(0.00)
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                                        $subtotal = (float) $get('subtotal');
                                                        $shipping = (float) $state;
                                                        $set('total', $subtotal + $shipping);
                                                    }),
                                                TextInput::make('total')
                                                    ->label('Total General')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->readonly()
                                                    ->default(0.00),
                                            ]),
                                    ]),

                                // 2. Shopping Cart (Repeater) with searchable select
                                Section::make('Artículos en el Carrito')
                                    ->description('Agrega las presentaciones de productos y sus cantidades respectivas. Búsqueda rápida habilitada, no se permiten productos duplicados.')
                                    ->components([
                                        Repeater::make('items')
                                            ->relationship('items')
                                            ->components([
                                                Select::make('product_variant_id')
                                                    ->label('Buscar Presentación de Producto')
                                                    ->options(fn() => ProductVariant::with('product')->get()->mapWithKeys(function ($variant) {
                                                        return [$variant->id => ($variant->product->name ?? 'Producto') . ' - ' . $variant->name . ' (SKU: ' . $variant->sku . ')'];
                                                    }))
                                                    ->required()
                                                    ->searchable()
                                                    ->preload()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                                        $variant = ProductVariant::find($state);
                                                        if ($variant) {
                                                            $set('price', $variant->price);
                                                            $set('total', $variant->price);
                                                        }
                                                    })
                                                    ->rules([
                                                        function (Get $get) {
                                                            return function (string $attribute, $value, \Closure $fail) use ($get) {
                                                                $items = $get('../../') ?? [];
                                                                $variantIds = collect($items)->pluck('product_variant_id')->filter()->toArray();
                                                                $occurrences = array_count_values($variantIds);
                                                                if (isset($occurrences[$value]) && $occurrences[$value] > 1) {
                                                                    $fail('Este producto ya ha sido agregado al pedido.');
                                                                }
                                                            };
                                                        },
                                                    ]),
                                                TextInput::make('quantity')
                                                    ->label('Cantidad')
                                                    ->numeric()
                                                    ->default(1)
                                                    ->required()
                                                    ->live()
                                                    ->afterStateUpdated(function (Set $set, Get $get, ?string $state) {
                                                        $price = (float) $get('price');
                                                        $qty = (int) $state;
                                                        $set('total', $price * $qty);
                                                    }),
                                                TextInput::make('price')
                                                    ->label('Precio Unitario')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->required()
                                                    ->readonly()
                                                    ->live(),
                                                TextInput::make('total')
                                                    ->label('Total Línea')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->required()
                                                    ->readonly(),
                                            ])
                                            ->columns(4)
                                            ->default([])
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                // Recalculate Subtotal, Tax, and Total
                                                $items = $get('items') ?? [];
                                                $subtotal = 0.0;
                                                foreach ($items as $item) {
                                                    $subtotal += (float) ($item['total'] ?? 0.0);
                                                }
                                                // Calculate tax (IGV is 18%)
                                                $tax = $subtotal * 0.18;
                                                $shipping = (float) $get('shipping_cost');
                                                $total = $subtotal + $shipping;

                                                $set('subtotal', $subtotal);
                                                $set('tax', $tax);
                                                $set('total', $total);
                                            }),
                                    ]),

                                // 3. Status and Transaction Section at the bottom
                                Section::make('Estados y Transacción')
                                    ->components([
                                        Grid::make(3)
                                            ->components([
                                                Select::make('status')
                                                    ->label('Estado de Envío')
                                                    ->options([
                                                        'pending' => 'Pendiente',
                                                        'preparing' => 'En Preparación',
                                                        'shipped' => 'Enviado',
                                                        'delivered' => 'Entregado',
                                                        'cancelled' => 'Cancelado',
                                                    ])
                                                    ->required()
                                                    ->default('pending'),
                                                Select::make('payment_status')
                                                    ->label('Estado de Pago')
                                                    ->options([
                                                        'pending' => 'Pendiente',
                                                        'paid' => 'Pagado',
                                                        'failed' => 'Fallido',
                                                        'refunded' => 'Reembolsado',
                                                    ])
                                                    ->required()
                                                    ->default('pending'),
                                                TextInput::make('payment_method')
                                                    ->label('Método de Pago')
                                                    ->placeholder('Ej: Contra entrega, Transferencia, Yape')
                                                    ->required(),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])->columns(1);
    }
}
