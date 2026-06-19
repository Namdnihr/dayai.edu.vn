<?php

namespace App\Filament\Resources\LeadAssignments\Pages;

use App\Filament\Resources\LeadAssignments\LeadAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadAssignments extends ListRecords
{
    protected static string $resource = LeadAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
