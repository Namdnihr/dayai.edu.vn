<?php

namespace App\Filament\Resources\VideoLessons\Schemas;

use Filament\Schemas\Schema;

class VideoLessonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components(VideoLessonFormComponents::make());
    }
}
