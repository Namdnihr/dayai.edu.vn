<?php

namespace App\Filament\Resources\ProgressReports\Pages;

use App\Filament\Resources\ProgressReports\ProgressReportResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProgressReport extends ViewRecord
{
    protected static string $resource = ProgressReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
