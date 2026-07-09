<?php

namespace App\Filament\Resources\Claims;

use App\Filament\Resources\Claims\Pages\EditClaim;
use App\Filament\Resources\Claims\Pages\ListClaims;
use App\Filament\Resources\Claims\Schemas\ClaimForm;
use App\Filament\Resources\Claims\Tables\ClaimTable;
use App\Models\Claim;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ClaimResource extends Resource
{
    protected static ?string $model = Claim::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'claim_code';

    protected static ?string $navigationLabel = 'Libro Reclamaciones';
    
    protected static ?string $pluralModelLabel = 'Libro de Reclamaciones';
    
    protected static ?string $modelLabel = 'Reclamación / Queja';

    protected static string|\UnitEnum|null $navigationGroup = 'Atención al Cliente';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return ClaimForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClaimTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClaims::route('/'),
            'edit' => EditClaim::route('/{record}/edit'),
        ];
    }
}
