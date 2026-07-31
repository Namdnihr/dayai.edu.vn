<?php

namespace App\Filament\Resources\ClassGroups;

use App\Filament\Resources\ClassGroups\Pages\CreateClassGroup;
use App\Filament\Resources\ClassGroups\Pages\EditClassGroup;
use App\Filament\Resources\ClassGroups\Pages\ListClassGroups;
use App\Filament\Resources\ClassGroups\Pages\ViewClassGroup;
use App\Filament\Resources\ClassGroups\Schemas\ClassGroupForm;
use App\Filament\Resources\ClassGroups\Schemas\ClassGroupInfolist;
use App\Filament\Resources\ClassGroups\Tables\ClassGroupsTable;
use App\Models\ClassGroup;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ClassGroupResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = ClassGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static ?string $navigationLabel = 'Lớp học';

    protected static ?string $modelLabel = 'lớp học';

    protected static ?string $pluralModelLabel = 'lớp học';

    protected static string|UnitEnum|null $navigationGroup = 'Đào tạo & LMS';

    protected static ?int $navigationSort = 80;

    public static function form(Schema $schema): Schema
    {
        return ClassGroupForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ClassGroupInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ClassGroupsTable::configure($table);
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
            'index' => ListClassGroups::route('/'),
            'create' => CreateClassGroup::route('/create'),
            'view' => ViewClassGroup::route('/{record}'),
            'edit' => EditClassGroup::route('/{record}/edit'),
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
