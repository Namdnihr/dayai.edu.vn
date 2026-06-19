<?php

namespace App\Filament\Resources\ConsultationActivities\Pages;

use App\Filament\Resources\ConsultationActivities\ConsultationActivityResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewConsultationActivity extends ViewRecord
{
    protected static string $resource = ConsultationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
