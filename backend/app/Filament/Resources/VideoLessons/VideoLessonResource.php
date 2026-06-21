<?php

namespace App\Filament\Resources\VideoLessons;

use App\Filament\Resources\VideoLessons\Pages\CreateVideoLesson;
use App\Filament\Resources\VideoLessons\Pages\EditVideoLesson;
use App\Filament\Resources\VideoLessons\Pages\ListVideoLessons;
use App\Filament\Resources\VideoLessons\Pages\ViewVideoLesson;
use App\Filament\Resources\VideoLessons\Schemas\VideoLessonForm;
use App\Filament\Resources\VideoLessons\Schemas\VideoLessonInfolist;
use App\Filament\Resources\VideoLessons\Tables\VideoLessonsTable;
use App\Models\VideoLesson;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class VideoLessonResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = VideoLesson::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::VideoCamera;

    protected static ?string $navigationLabel = 'Video Academy';

    protected static ?string $modelLabel = 'video bài học';

    protected static ?string $pluralModelLabel = 'video academy';

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return VideoLessonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VideoLessonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VideoLessonsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVideoLessons::route('/'),
            'create' => CreateVideoLesson::route('/create'),
            'view' => ViewVideoLesson::route('/{record}'),
            'edit' => EditVideoLesson::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
