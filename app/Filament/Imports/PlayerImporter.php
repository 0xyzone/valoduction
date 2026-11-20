<?php

namespace App\Filament\Imports;

use App\Models\Player;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class PlayerImporter extends Importer
{
    protected static ?string $model = Player::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('team')
                ->relationship(resolveUsing: ['short_name', 'name'])
                ->examples(['SEN', 'PRX']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['Tyson Ngo', 'Jason Susanto']),
            ImportColumn::make('riot_id')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->examples(['TenZ', 'f0rsakeN']),
            ImportColumn::make('riot_tag')
                ->requiredMapping()
                ->rules(['required', 'max:16'])
                ->examples(['001', 'PRX']),
            ImportColumn::make('role')
                ->rules(['max:255'])
                ->examples(['Duelist', 'Flex']),
            ImportColumn::make('country')
                ->rules(['max:64'])
                ->examples(['CA', 'ID']),
            ImportColumn::make('avatar')
                ->rules(['max:255'])
                ->examples(['', '']),
            ImportColumn::make('is_active')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean'])
                ->examples(['1', '1']),
        ];
    }

    public function resolveRecord(): Player
    {
        return Player::firstOrNew([
            'riot_id' => $this->data['riot_id'],
            'riot_tag' => $this->data['riot_tag'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your player import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
