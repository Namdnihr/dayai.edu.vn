<?php

namespace App\Filament\Resources\ConsultationActivities\Pages;

use App\Filament\Resources\ConsultationActivities\ConsultationActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListConsultationActivities extends ListRecords
{
    protected static string $resource = ConsultationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
