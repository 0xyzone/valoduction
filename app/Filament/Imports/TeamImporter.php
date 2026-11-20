<?php

namespace App\Filament\Imports;

use App\Models\Team;
use App\Models\Tournament;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Select;
use Illuminate\Support\Number;

class TeamImporter extends Importer
{
    protected static ?string $model = Team::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['Sentinels', 'Paper Rex']),
            ImportColumn::make('short_name')
                ->requiredMapping()
                ->rules(['required', 'max:16'])
                ->examples(['SEN', 'PRX']),
            ImportColumn::make('logo')
                ->rules(['max:255'])
                ->examples(['', '']),
            ImportColumn::make('primary_color')
                ->rules(['max:16'])
                ->examples(['#FF4655', '#3B82F6']),
            ImportColumn::make('region')
                ->rules(['max:255'])
                ->examples(['Americas', 'APAC']),
        ];
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Select::make('tournament_id')
                ->label('Attach Imported Teams to Tournament (Optional)')
                ->options(fn (): array => Tournament::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable(),
        ];
    }

    public function resolveRecord(): Team
    {
        return Team::firstOrNew([
            'short_name' => $this->data['short_name'],
        ]);
    }

    protected function afterSave(): void
    {
        $tournamentId = $this->options['tournament_id'] ?? null;

        if (filled($tournamentId)) {
            $this->record->tournaments()->syncWithoutDetaching([(int) $tournamentId]);
        }
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your team import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
