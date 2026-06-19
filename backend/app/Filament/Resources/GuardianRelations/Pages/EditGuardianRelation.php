<?php

namespace App\Filament\Resources\GuardianRelations\Pages;

use App\Filament\Resources\GuardianRelations\GuardianRelationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditGuardianRelation extends EditRecord
{
    protected static string $resource = GuardianRelationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
