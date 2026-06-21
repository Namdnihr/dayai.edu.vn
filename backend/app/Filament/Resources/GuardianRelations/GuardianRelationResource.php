<?php

namespace App\Filament\Resources\GuardianRelations;

use App\Filament\Resources\GuardianRelations\Pages\CreateGuardianRelation;
use App\Filament\Resources\GuardianRelations\Pages\EditGuardianRelation;
use App\Filament\Resources\GuardianRelations\Pages\ListGuardianRelations;
use App\Filament\Resources\GuardianRelations\Pages\ViewGuardianRelation;
use App\Filament\Resources\GuardianRelations\Schemas\GuardianRelationForm;
use App\Filament\Resources\GuardianRelations\Schemas\GuardianRelationInfolist;
use App\Filament\Resources\GuardianRelations\Tables\GuardianRelationsTable;
use App\Models\GuardianRelation;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class GuardianRelationResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = GuardianRelation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $navigationLabel = 'Quan hệ phụ huynh';

    protected static ?string $modelLabel = 'quan hệ phụ huynh';

    protected static ?string $pluralModelLabel = 'quan hệ phụ huynh';

    protected static string|UnitEnum|null $navigationGroup = 'Dữ liệu nền';

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return GuardianRelationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GuardianRelationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuardianRelationsTable::configure($table);
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
            'index' => ListGuardianRelations::route('/'),
            'create' => CreateGuardianRelation::route('/create'),
            'view' => ViewGuardianRelation::route('/{record}'),
            'edit' => EditGuardianRelation::route('/{record}/edit'),
        ];
    }
}
