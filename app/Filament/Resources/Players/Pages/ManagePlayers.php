<?php

namespace App\Filament\Resources\Players\Pages;

use App\Filament\Exports\PlayerExporter;
use App\Filament\Imports\PlayerImporter;
use App\Filament\Resources\Players\PlayerResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePlayers extends ManageRecords
{
    protected static string $resource = PlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(PlayerImporter::class),
            ExportAction::make()
                ->exporter(PlayerExporter::class),
            CreateAction::make(),
        ];
    }
}
