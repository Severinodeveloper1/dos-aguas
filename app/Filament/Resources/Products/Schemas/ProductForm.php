<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Ficha de Producto')
                    ->tabs([
                        Tab::make('Información General')
                            ->icon('heroicon-o-information-circle')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        TextInput::make('name')
                                            ->label('Nombre del Producto')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                            ->placeholder('Ej: Chocolate Hierba Luisa 70%'),
                                        TextInput::make('slug')
                                            ->label('Slug / URL Amigable')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->placeholder('ej: chocolate-hierba-luisa-70'),
                                    ]),
                                Select::make('category_id')
                                    ->label('Categoría')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                RichEditor::make('description')
                                    ->label('Descripción del Producto')
                                    ->columnSpanFull(),
                                FileUpload::make('images')
                                    ->label('Imágenes del Producto')
                                    ->multiple()
                                    ->disk('public')
                                    ->directory('products')
                                    ->image()
                                    ->imageEditor()
                                    ->maxFiles(10)
                                    ->helperText('Sube hasta 10 imágenes del producto.')
                                    ->columns(3),
                            ]),

                        Tab::make('Presentaciones y Stock')
                            ->icon('heroicon-o-cube')
                            ->components([
                                Section::make('Presentaciones y Control de Stock')
                                    ->description('Defina los tamaños de empaque o cobertura (ej. individual, 250g, 1kg, 5kg) y sus precios.')
                                    ->components([
                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->components([
                                                TextInput::make('name')
                                                    ->label('Nombre Presentación')
                                                    ->placeholder('Ej: Barra Individual, Cobertura 1kg')
                                                    ->required(),
                                                TextInput::make('sku')
                                                    ->label('SKU')
                                                    ->placeholder('DA-CHO-HL70-1KG')
                                                    ->required()
                                                    ->unique(ignoreRecord: true),
                                                TextInput::make('weight')
                                                    ->label('Peso (gramos)')
                                                    ->numeric()
                                                    ->placeholder('Ej: 1000')
                                                    ->suffix('g')
                                                    ->required(),
                                                TextInput::make('price')
                                                    ->label('Precio de Venta')
                                                    ->numeric()
                                                    ->prefix('S/.')
                                                    ->required(),
                                                TextInput::make('stock')
                                                    ->label('Stock Actual')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->required(),
                                                Toggle::make('is_active')
                                                    ->label('Disponible')
                                                    ->default(true),
                                            ])
                                            ->columns(3)
                                            ->default([])
                                            ->minItems(1)
                                            ->itemLabel(fn(array $state): ?string => ($state['name'] ?? 'Variante') . ' (' . ($state['sku'] ?? 'Sin SKU') . ')')
                                            ->collapsible(),
                                    ]),
                            ]),

                        Tab::make('Especificaciones y Nutrición')
                            ->icon('heroicon-o-document-text')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        RichEditor::make('tasting_notes')
                                            ->label('Notas de Cata')
                                            ->placeholder('Ej: Notas cítricas de frutos amarillos.')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                        RichEditor::make('natural_benefits')
                                            ->label('Beneficios Naturales')
                                            ->placeholder('Ej: Excelente digestivo natural.')
                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                    ]),
                                Section::make('Tabla Valor Nutricional')
                                    ->components([
                                        Repeater::make('nutritional_values')
                                            ->label('Parámetros Nutricionales')
                                            ->components([
                                                TextInput::make('label')
                                                    ->label('Parámetro (Ej: Grasas Sat.)')
                                                    ->required(),
                                                TextInput::make('value')
                                                    ->label('Valor (Ej: 2.5 g / 10%)')
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                            ->default([])
                                            ->itemLabel(fn(array $state): ?string => ($state['label'] ?? 'Nutriente') . ': ' . ($state['value'] ?? ''))
                                            ->collapsible()
                                            ->compact(),
                                    ]),
                            ]),

                        Tab::make('Configuración SEO')
                            ->icon('heroicon-o-globe-alt')
                            ->components([
                                Section::make('Metadatos SEO')
                                    ->components([
                                        TextInput::make('meta_title')
                                            ->label('Meta Título')
                                            ->maxLength(60),
                                        Textarea::make('meta_description')
                                            ->label('Meta Descripción')
                                            ->maxLength(160)
                                            ->rows(3),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])->columns(1);
    }
}
