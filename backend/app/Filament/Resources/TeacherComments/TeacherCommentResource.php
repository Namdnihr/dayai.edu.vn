<?php

namespace App\Filament\Resources\TeacherComments;

use App\Filament\Resources\TeacherComments\Pages\CreateTeacherComment;
use App\Filament\Resources\TeacherComments\Pages\EditTeacherComment;
use App\Filament\Resources\TeacherComments\Pages\ListTeacherComments;
use App\Filament\Resources\TeacherComments\Pages\ViewTeacherComment;
use App\Filament\Resources\TeacherComments\Schemas\TeacherCommentForm;
use App\Filament\Resources\TeacherComments\Schemas\TeacherCommentInfolist;
use App\Filament\Resources\TeacherComments\Tables\TeacherCommentsTable;
use App\Models\TeacherComment;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TeacherCommentResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = TeacherComment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Nhận xét giáo viên';

    protected static ?string $modelLabel = 'nhận xét giáo viên';

    protected static ?string $pluralModelLabel = 'nhận xét giáo viên';

    protected static string|UnitEnum|null $navigationGroup = 'LMS & Khóa học';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return TeacherCommentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TeacherCommentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeacherCommentsTable::configure($table);
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
            'index' => ListTeacherComments::route('/'),
            'create' => CreateTeacherComment::route('/create'),
            'view' => ViewTeacherComment::route('/{record}'),
            'edit' => EditTeacherComment::route('/{record}/edit'),
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
