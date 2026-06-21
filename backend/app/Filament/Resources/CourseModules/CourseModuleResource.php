<?php

namespace App\Filament\Resources\CourseModules;

use App\Filament\Resources\CourseModules\Pages\CreateCourseModule;
use App\Filament\Resources\CourseModules\Pages\EditCourseModule;
use App\Filament\Resources\CourseModules\Pages\ListCourseModules;
use App\Filament\Resources\CourseModules\Pages\ViewCourseModule;
use App\Filament\Resources\CourseModules\Schemas\CourseModuleForm;
use App\Filament\Resources\CourseModules\Schemas\CourseModuleInfolist;
use App\Filament\Resources\CourseModules\Tables\CourseModulesTable;
use App\Models\CourseModule;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CourseModuleResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = CourseModule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    protected static ?string $navigationLabel = 'Module khóa học';

    protected static ?string $modelLabel = 'module khóa học';

    protected static ?string $pluralModelLabel = 'module khóa học';

    protected static string|UnitEnum|null $navigationGroup = 'Vận hành trung tâm';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return CourseModuleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseModuleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseModulesTable::configure($table);
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
            'index' => ListCourseModules::route('/'),
            'create' => CreateCourseModule::route('/create'),
            'view' => ViewCourseModule::route('/{record}'),
            'edit' => EditCourseModule::route('/{record}/edit'),
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
