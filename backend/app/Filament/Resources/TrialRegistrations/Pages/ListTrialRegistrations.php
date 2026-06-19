<?php

namespace App\Filament\Resources\TrialRegistrations\Pages;

use App\Filament\Resources\TrialRegistrations\TrialRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTrialRegistrations extends ListRecords
{
    protected static string $resource = TrialRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
