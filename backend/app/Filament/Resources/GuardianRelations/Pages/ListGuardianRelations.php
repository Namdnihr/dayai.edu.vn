<?php

namespace App\Filament\Resources\GuardianRelations\Pages;

use App\Filament\Resources\GuardianRelations\GuardianRelationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGuardianRelations extends ListRecords
{
    protected static string $resource = GuardianRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
