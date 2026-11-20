<?php

namespace App\Filament\Resources\Players\Schemas;

use App\Support\Valorant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Player Profile')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('avatar')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('players')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('team_id')
                            ->relationship('team', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('riot_id')
                            ->label('Riot ID')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('riot_tag')
                            ->label('Riot Tag')
                            ->prefix('#')
                            ->required()
                            ->maxLength(16),
                        Select::make('role')
                            ->options(Valorant::roles()),
                        TextInput::make('country')
                            ->maxLength(64),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}
