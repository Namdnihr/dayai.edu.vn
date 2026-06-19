<?php

namespace App\Filament\Resources\LeadAssignments\Pages;

use App\Filament\Resources\LeadAssignments\LeadAssignmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLeadAssignment extends ViewRecord
{
    protected static string $resource = LeadAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
