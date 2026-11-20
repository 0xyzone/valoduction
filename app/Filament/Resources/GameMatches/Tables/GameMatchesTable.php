<?php

namespace App\Filament\Resources\GameMatches\Tables;

use App\Models\GameMatch;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GameMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tournament.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('round_label')
                    ->label('Round')
                    ->searchable(),
                TextColumn::make('teamA.short_name')
                    ->label('Team A')
                    ->badge(),
                TextColumn::make('score')
                    ->label('Score')
                    ->state(fn (GameMatch $record): string => "{$record->team_a_score} - {$record->team_b_score}"),
                TextColumn::make('teamB.short_name')
                    ->label('Team B')
                    ->badge(),
                TextColumn::make('best_of')
                    ->formatStateUsing(fn (int $state): string => "Bo{$state}"),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'live', 'veto' => 'danger',
                        'completed' => 'success',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('scheduled_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('tournament_id')
                    ->label('Tournament')
                    ->relationship('tournament', 'name'),
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'veto' => 'Veto',
                        'live' => 'Live',
                        'completed' => 'Completed',
                    ]),
            ])
            ->recordActions([
                Action::make('vetoOverlay')
                    ->label('Veto OBS')
                    ->icon(Heroicon::OutlinedTv)
                    ->url(fn (GameMatch $record): string => route('overlays.veto', $record))
                    ->openUrlInNewTab(),
                Action::make('postMatchOverlay')
                    ->label('Post-Match OBS')
                    ->icon(Heroicon::OutlinedChartBar)
                    ->url(fn (GameMatch $record): string => route('overlays.post-match', $record))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
