<?php

namespace App\Filament\Resources\VideoLessons\Pages;

use App\Filament\Resources\VideoLessons\VideoLessonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVideoLesson extends ViewRecord
{
    protected static string $resource = VideoLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
