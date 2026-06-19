<?php

namespace App\Filament\Resources\TrialRegistrations\Pages;

use App\Filament\Resources\TrialRegistrations\TrialRegistrationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTrialRegistration extends ViewRecord
{
    protected static string $resource = TrialRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
