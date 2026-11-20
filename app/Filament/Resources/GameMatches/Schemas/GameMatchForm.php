<?php

namespace App\Filament\Resources\GameMatches\Schemas;

use App\Models\Player;
use App\Models\Team;
use App\Support\Valorant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class GameMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Match Setup')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('tournament_id')
                            ->relationship('tournament', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('round_label')
                            ->placeholder('e.g. Upper Quarterfinal, Grand Final')
                            ->maxLength(255),
                        Select::make('team_a_id')
                            ->label('Team A')
                            ->relationship('teamA', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),
                        Select::make('team_b_id')
                            ->label('Team B')
                            ->relationship('teamB', 'name')
                            ->required()
                            ->different('team_a_id')
                            ->searchable()
                            ->preload()
                            ->live(),
                        Select::make('best_of')
                            ->options([
                                1 => 'Best of 1',
                                3 => 'Best of 3',
                                5 => 'Best of 5',
                            ])
                            ->default(3)
                            ->required(),
                        Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'veto' => 'Veto',
                                'live' => 'Live',
                                'completed' => 'Completed',
                            ])
                            ->default('scheduled')
                            ->required(),
                        DateTimePicker::make('scheduled_at')
                            ->label('Scheduled At'),
                        Select::make('winner_team_id')
                            ->label('Winner')
                            ->options(fn (Get $get): array => self::matchTeams($get))
                            ->searchable(),
                        TextInput::make('team_a_score')
                            ->label('Team A Series Score')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('team_b_score')
                            ->label('Team B Series Score')
                            ->numeric()
                            ->minValue(0)
                            ->default(0),
                    ]),

                Section::make('Live Map Veto')
                    ->description('Updates broadcast live to the OBS Veto Browser Source via Laravel Reverb.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('vetos')
                            ->relationship()
                            ->orderColumn('step_order')
                            ->columns(4)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('team_id')
                                    ->label('Team')
                                    ->options(fn (Get $get): array => self::matchTeams($get, '../../'))
                                    ->placeholder('Decider'),
                                Select::make('action')
                                    ->options([
                                        'ban' => 'Ban',
                                        'pick' => 'Pick',
                                        'decider' => 'Decider',
                                    ])
                                    ->required(),
                                Select::make('map_name')
                                    ->label('Map')
                                    ->options(Valorant::maps())
                                    ->searchable()
                                    ->required(),
                                Select::make('side')
                                    ->label('Starting Side')
                                    ->options([
                                        'attack' => 'Attack',
                                        'defense' => 'Defense',
                                    ]),
                            ]),
                    ]),

                Section::make('Maps & Post-Match Player Stats')
                    ->description('Updates broadcast live to the OBS Post-Match Browser Source via Laravel Reverb.')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('maps')
                            ->relationship()
                            ->orderColumn('map_number')
                            ->columns(3)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('map_name')
                                    ->label('Map')
                                    ->options(Valorant::maps())
                                    ->searchable()
                                    ->required(),
                                Select::make('status')
                                    ->options([
                                        'upcoming' => 'Upcoming',
                                        'live' => 'Live',
                                        'completed' => 'Completed',
                                    ])
                                    ->default('upcoming')
                                    ->required(),
                                Select::make('winner_team_id')
                                    ->label('Map Winner')
                                    ->options(fn (Get $get): array => self::matchTeams($get, '../../')),
                                TextInput::make('team_a_score')
                                    ->label('Team A Rounds')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                                TextInput::make('team_b_score')
                                    ->label('Team B Rounds')
                                    ->numeric()
                                    ->minValue(0)
                                    ->default(0),
                                Repeater::make('playerStats')
                                    ->relationship()
                                    ->label('Player Scoreboard')
                                    ->columns(4)
                                    ->columnSpanFull()
                                    ->schema([
                                        Select::make('team_id')
                                            ->label('Team')
                                            ->options(fn (Get $get): array => self::matchTeams($get, '../../../../'))
                                            ->required(),
                                        Select::make('player_id')
                                            ->label('Player')
                                            ->options(fn (): array => Player::query()->orderBy('name')->pluck('name', 'id')->all())
                                            ->searchable()
                                            ->required(),
                                        Select::make('agent')
                                            ->options(Valorant::agents())
                                            ->searchable(),
                                        TextInput::make('acs')
                                            ->label('ACS')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('kills')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('deaths')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('assists')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('adr')
                                            ->label('ADR')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('hs_percentage')
                                            ->label('HS%')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('first_kills')
                                            ->label('FK')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('first_deaths')
                                            ->label('FD')
                                            ->numeric()
                                            ->default(0),
                                        TextInput::make('credits_spent')
                                            ->label('Credits')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    /**
     * @return array<int, string>
     */
    private static function matchTeams(Get $get, string $prefix = ''): array
    {
        $ids = array_filter([
            $get($prefix.'team_a_id'),
            $get($prefix.'team_b_id'),
        ]);

        if ($ids === []) {
            return [];
        }

        return Team::query()
            ->whereIn('id', $ids)
            ->pluck('name', 'id')
            ->all();
    }
}
