<?php

namespace App\Filament\Exports;

use App\Models\Player;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Str;

class PlayerExporter extends Exporter
{
    protected static ?string $model = Player::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('team.name'),
            ExportColumn::make('name'),
            ExportColumn::make('riot_id'),
            ExportColumn::make('riot_tag'),
            ExportColumn::make('role'),
            ExportColumn::make('country'),
            ExportColumn::make('avatar'),
            ExportColumn::make('is_active'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your player export has completed and '.Str::of('row')->counted($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Str::of('row')->counted($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
