<?php

namespace App\Filament\Resources\Teams\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Team Identity')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('teams')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('short_name')
                            ->label('Tag / Short Name')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(16),
                        ColorPicker::make('primary_color')
                            ->label('Brand Color'),
                        TextInput::make('region')
                            ->placeholder('e.g. APAC, EMEA, Americas')
                            ->maxLength(255),
                    ]),
                Section::make('Tournaments')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('tournaments')
                            ->relationship('tournaments', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
