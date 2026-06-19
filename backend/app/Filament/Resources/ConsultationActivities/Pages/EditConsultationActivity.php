<?php

namespace App\Filament\Resources\ConsultationActivities\Pages;

use App\Filament\Resources\ConsultationActivities\ConsultationActivityResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditConsultationActivity extends EditRecord
{
    protected static string $resource = ConsultationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
