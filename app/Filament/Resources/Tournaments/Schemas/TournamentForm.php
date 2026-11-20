<?php

namespace App\Filament\Resources\Tournaments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tournament Details')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('tournaments')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create'
                                ? $set('slug', Str::slug($state ?? ''))
                                : null),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Select::make('status')
                            ->options([
                                'upcoming' => 'Upcoming',
                                'live' => 'Live',
                                'completed' => 'Completed',
                            ])
                            ->default('upcoming')
                            ->required(),
                        Select::make('format')
                            ->options([
                                'single_elimination' => 'Single Elimination',
                                'double_elimination' => 'Double Elimination',
                                'round_robin' => 'Round Robin',
                                'swiss' => 'Swiss',
                                'custom' => 'Custom',
                            ])
                            ->default('single_elimination')
                            ->required(),
                        DateTimePicker::make('starts_at')
                            ->label('Starts At'),
                        DateTimePicker::make('ends_at')
                            ->label('Ends At'),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Participating Teams')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('teams')
                            ->relationship('teams', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
