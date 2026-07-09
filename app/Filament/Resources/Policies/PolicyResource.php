<?php

namespace App\Filament\Resources\Policies;

use App\Filament\Resources\Policies\Pages\CreatePolicy;
use App\Filament\Resources\Policies\Pages\EditPolicy;
use App\Filament\Resources\Policies\Pages\ListPolicies;
use App\Filament\Resources\Policies\Schemas\PolicyForm;
use App\Filament\Resources\Policies\Tables\PolicyTable;
use App\Models\Policy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PolicyResource extends Resource
{
    protected static ?string $model = Policy::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationLabel = 'Políticas';
    
    protected static ?string $pluralModelLabel = 'Políticas';
    
    protected static ?string $modelLabel = 'Política';

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PolicyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PolicyTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPolicies::route('/'),
            'create' => CreatePolicy::route('/create'),
            'edit' => EditPolicy::route('/{record}/edit'),
        ];
    }
}
