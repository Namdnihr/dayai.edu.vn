<?php

namespace App\Filament\Resources\TeacherComments\Pages;

use App\Filament\Resources\TeacherComments\TeacherCommentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTeacherComment extends ViewRecord
{
    protected static string $resource = TeacherCommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
