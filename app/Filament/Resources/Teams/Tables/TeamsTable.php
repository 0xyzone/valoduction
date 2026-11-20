<?php

namespace App\Filament\Resources\Teams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TeamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->disk('public')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('short_name')
                    ->label('Tag')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('primary_color')
                    ->label('Color'),
                TextColumn::make('region')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('players_count')
                    ->counts('players')
                    ->label('Players'),
                TextColumn::make('tournaments_count')
                    ->counts('tournaments')
                    ->label('Tournaments'),
            ])
            ->recordActions([
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
