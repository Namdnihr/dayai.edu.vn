<?php

namespace App\Filament\Resources\LeadAssignments\Pages;

use App\Filament\Resources\LeadAssignments\LeadAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadAssignment extends EditRecord
{
    protected static string $resource = LeadAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
