<?php

namespace App\Filament\Resources\AutomationWorkflows\Pages;

use App\Filament\Resources\AutomationWorkflows\AutomationWorkflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAutomationWorkflows extends ListRecords
{
    protected static string $resource = AutomationWorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
