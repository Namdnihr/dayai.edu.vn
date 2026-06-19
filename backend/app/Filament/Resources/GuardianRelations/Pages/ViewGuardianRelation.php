<?php

namespace App\Filament\Resources\GuardianRelations\Pages;

use App\Filament\Resources\GuardianRelations\GuardianRelationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGuardianRelation extends ViewRecord
{
    protected static string $resource = GuardianRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
