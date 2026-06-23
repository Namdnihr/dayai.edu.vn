<?php

namespace App\Filament\Resources\AutomationMessages\Pages;

use App\Filament\Resources\AutomationMessages\AutomationMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAutomationMessages extends ListRecords
{
    protected static string $resource = AutomationMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
