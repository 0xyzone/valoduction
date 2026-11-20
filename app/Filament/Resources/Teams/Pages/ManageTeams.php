<?php

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Exports\TeamExporter;
use App\Filament\Imports\TeamImporter;
use App\Filament\Resources\Teams\TeamResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTeams extends ManageRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(TeamImporter::class),
            ExportAction::make()
                ->exporter(TeamExporter::class),
            CreateAction::make(),
        ];
    }
}
