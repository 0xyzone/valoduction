<?php

namespace App\Filament\Resources\GameMatches;

use App\Filament\Resources\GameMatches\Pages\ManageGameMatches;
use App\Filament\Resources\GameMatches\Schemas\GameMatchForm;
use App\Filament\Resources\GameMatches\Tables\GameMatchesTable;
use App\Models\GameMatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GameMatchResource extends Resource
{
    protected static ?string $model = GameMatch::class;

    protected static ?string $navigationLabel = 'Matches';

    protected static ?string $modelLabel = 'Match';

    protected static ?string $pluralModelLabel = 'Matches';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;

    protected static string|UnitEnum|null $navigationGroup = 'Tournament Ops';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return GameMatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GameMatchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGameMatches::route('/'),
        ];
    }
}
