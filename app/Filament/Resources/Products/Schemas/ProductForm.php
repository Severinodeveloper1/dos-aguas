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

                        // ── TAB 1: INFORMACIÓN GENERAL ─────────────────────────────
                        Tab::make('Información General')
                            ->icon('heroicon-o-information-circle')
                            ->components([
                                Grid::make(2)
                                    ->components([
                                        Select::make('category_id')
                                            ->label('Categoría')
                                            ->relationship('category', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload(),
                                        TextInput::make('slug')
                                            ->label('Slug / URL Amigable')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->placeholder('ej: chocolate-hierba-luisa-70'),
                                    ]),

                                Tabs::make('Contenido Multilingüe')
                                    ->tabs([
                                        Tab::make('Español (ES)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('name')
                                                    ->label('Nombre del Producto (Español)')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                                    ->placeholder('Ej: Chocolate Hierba Luisa 70%'),
                                                RichEditor::make('description')
                                                    ->label('Descripción (Español)')
                                                    ->columnSpanFull(),
                                            ]),

                                        Tab::make('Inglés (EN)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('name_en')
                                                    ->label('Nombre del Producto (Inglés)')
                                                    ->maxLength(255)
                                                    ->placeholder('Ej: Lemon Verbena Chocolate 70%'),
                                                RichEditor::make('description_en')
                                                    ->label('Descripción (Inglés)')
                                                    ->columnSpanFull(),
                                            ]),

                                        Tab::make('Alemán (DE)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('name_de')
                                                    ->label('Nombre del Producto (Alemán)')
                                                    ->maxLength(255)
                                                    ->placeholder('Ej: Zitronenstrauch Schokolade 70%'),
                                                RichEditor::make('description_de')
                                                    ->label('Descripción (Alemán)')
                                                    ->columnSpanFull(),
                                            ]),
                                    ])->columnSpanFull(),

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

                        // ── TAB 2: PRESENTACIONES Y STOCK ──────────────────────────
                        Tab::make('Presentaciones y Stock')
                            ->icon('heroicon-o-cube')
                            ->components([
                                Section::make('Presentaciones y Control de Stock')
                                    ->description('Defina los tamaños de empaque (ej. Barra Individual, Cobertura 1kg) y sus precios traducidos.')
                                    ->components([
                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->components([
                                                Grid::make(3)
                                                    ->components([
                                                        TextInput::make('name')
                                                            ->label('Nombre (Español)')
                                                            ->placeholder('Ej: Barra Individual 70g')
                                                            ->required(),
                                                        TextInput::make('name_en')
                                                            ->label('Nombre (Inglés)')
                                                            ->placeholder('Ej: Single Bar 70g'),
                                                        TextInput::make('name_de')
                                                            ->label('Nombre (Alemán)')
                                                            ->placeholder('Ej: Einzelne Tafel 70g'),
                                                    ]),
                                                Grid::make(3)
                                                    ->components([
                                                        TextInput::make('sku')
                                                            ->label('SKU')
                                                            ->placeholder('DA-CHO-HL70-1KG')
                                                            ->required()
                                                            ->unique(ignoreRecord: true),
                                                        TextInput::make('weight')
                                                            ->label('Peso (gramos)')
                                                            ->numeric()
                                                            ->placeholder('Ej: 70')
                                                            ->suffix('g')
                                                            ->required(),
                                                        TextInput::make('price')
                                                            ->label('Precio de Venta')
                                                            ->numeric()
                                                            ->prefix('S/.')
                                                            ->required(),
                                                    ]),
                                                Grid::make(2)
                                                    ->components([
                                                        TextInput::make('stock')
                                                            ->label('Stock Actual')
                                                            ->numeric()
                                                            ->default(0)
                                                            ->required(),
                                                        Toggle::make('is_active')
                                                            ->label('Disponible')
                                                            ->default(true),
                                                    ]),
                                            ])
                                            ->columns(1)
                                            ->default([])
                                            ->minItems(1)
                                            ->itemLabel(fn(array $state): ?string => ($state['name'] ?? 'Variante') . ' (' . ($state['sku'] ?? 'Sin SKU') . ')')
                                            ->collapsible(),
                                    ]),
                            ]),

                        // ── TAB 3: ESPECIFICACIONES Y NUTRICIÓN ───────────────────
                        Tab::make('Especificaciones y Nutrición')
                            ->icon('heroicon-o-document-text')
                            ->components([
                                Tabs::make('Traducción de Especificaciones')
                                    ->tabs([
                                        Tab::make('Español (ES)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                Grid::make(2)
                                                    ->components([
                                                        RichEditor::make('tasting_notes')
                                                            ->label('Notas de Cata (Español)')
                                                            ->placeholder('Ej: Notas cítricas de frutos amarillos.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                        RichEditor::make('natural_benefits')
                                                            ->label('Beneficios Naturales (Español)')
                                                            ->placeholder('Ej: Excelente digestivo natural.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                    ]),
                                                Section::make('Tabla Valor Nutricional (Español)')
                                                    ->components([
                                                        Repeater::make('nutritional_values')
                                                            ->label('Parámetros Nutricionales (Español)')
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

                                        Tab::make('Inglés (EN)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                Grid::make(2)
                                                    ->components([
                                                        RichEditor::make('tasting_notes_en')
                                                            ->label('Notas de Cata (Inglés)')
                                                            ->placeholder('Ej: Citrus notes of yellow fruits.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                        RichEditor::make('natural_benefits_en')
                                                            ->label('Beneficios Naturales (Inglés)')
                                                            ->placeholder('Ej: Excellent natural digestive aid.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                    ]),
                                                Section::make('Tabla Valor Nutricional (Inglés)')
                                                    ->components([
                                                        Repeater::make('nutritional_values_en')
                                                            ->label('Parámetros Nutricionales (Inglés)')
                                                            ->components([
                                                                TextInput::make('label')
                                                                    ->label('Nutrient (Ej: Sat. Fat)')
                                                                    ->required(),
                                                                TextInput::make('value')
                                                                    ->label('Value (Ej: 2.5 g / 10%)')
                                                                    ->required(),
                                                            ])
                                                            ->columns(2)
                                                            ->default([])
                                                            ->itemLabel(fn(array $state): ?string => ($state['label'] ?? 'Nutrient') . ': ' . ($state['value'] ?? ''))
                                                            ->collapsible()
                                                            ->compact(),
                                                    ]),
                                            ]),

                                        Tab::make('Alemán (DE)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                Grid::make(2)
                                                    ->components([
                                                        RichEditor::make('tasting_notes_de')
                                                            ->label('Notas de Cata (Alemán)')
                                                            ->placeholder('Ej: Zitrusnoten von gelben Früchten.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                        RichEditor::make('natural_benefits_de')
                                                            ->label('Beneficios Naturales (Alemán)')
                                                            ->placeholder('Ej: Ausgezeichnete natürliche Verdauungshilfe.')
                                                            ->toolbarButtons(['bold', 'italic', 'bulletList']),
                                                    ]),
                                                Section::make('Tabla Valor Nutricional (Alemán)')
                                                    ->components([
                                                        Repeater::make('nutritional_values_de')
                                                            ->label('Parámetros Nutricionales (Alemán)')
                                                            ->components([
                                                                TextInput::make('label')
                                                                    ->label('Nährwert (Ej: Gesättigte Fette)')
                                                                    ->required(),
                                                                TextInput::make('value')
                                                                    ->label('Wert (Ej: 2.5 g / 10%)')
                                                                    ->required(),
                                                            ])
                                                            ->columns(2)
                                                            ->default([])
                                                            ->itemLabel(fn(array $state): ?string => ($state['label'] ?? 'Nährwert') . ': ' . ($state['value'] ?? ''))
                                                            ->collapsible()
                                                            ->compact(),
                                                    ]),
                                            ]),
                                    ])->columnSpanFull(),
                            ]),

                        // ── TAB 4: CONFIGURACIÓN SEO ──────────────────────────────
                        Tab::make('Configuración SEO')
                            ->icon('heroicon-o-globe-alt')
                            ->components([
                                Tabs::make('SEO Multilingüe')
                                    ->tabs([
                                        Tab::make('Español (ES)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('meta_title')
                                                    ->label('Meta Título (Español)')
                                                    ->maxLength(60),
                                                Textarea::make('meta_description')
                                                    ->label('Meta Descripción (Español)')
                                                    ->maxLength(160)
                                                    ->rows(3),
                                            ]),
                                        Tab::make('Inglés (EN)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('meta_title_en')
                                                    ->label('Meta Título (Inglés)')
                                                    ->maxLength(60),
                                                Textarea::make('meta_description_en')
                                                    ->label('Meta Descripción (Inglés)')
                                                    ->maxLength(160)
                                                    ->rows(3),
                                            ]),
                                        Tab::make('Alemán (DE)')
                                            ->icon('heroicon-o-language')
                                            ->components([
                                                TextInput::make('meta_title_de')
                                                    ->label('Meta Título (Alemán)')
                                                    ->maxLength(60),
                                                Textarea::make('meta_description_de')
                                                    ->label('Meta Descripción (Alemán)')
                                                    ->maxLength(160)
                                                    ->rows(3),
                                            ]),
                                    ])->columnSpanFull(),
                            ]),

                    ])->columnSpanFull(),
            ]);
    }
}
