<?php

namespace App\Filament\Resources\ContentItems;

use App\Filament\Resources\ContentItems\Pages\CreateContentItem;
use App\Filament\Resources\ContentItems\Pages\EditContentItem;
use App\Filament\Resources\ContentItems\Pages\ListContentItems;
use App\Filament\Resources\ContentItems\Pages\ViewContentItem;
use App\Filament\Resources\ContentItems\Schemas\ContentItemForm;
use App\Filament\Resources\ContentItems\Schemas\ContentItemInfolist;
use App\Filament\Resources\ContentItems\Tables\ContentItemsTable;
use App\Models\ContentItem;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ContentItemResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = ContentItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

    protected static ?string $navigationLabel = 'Bài viết & tài nguyên';

    protected static ?string $modelLabel = 'bài viết';

    protected static ?string $pluralModelLabel = 'bài viết & tài nguyên';

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return ContentItemForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ContentItemInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentItemsTable::configure($table);
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
            'index' => ListContentItems::route('/'),
            'create' => CreateContentItem::route('/create'),
            'view' => ViewContentItem::route('/{record}'),
            'edit' => EditContentItem::route('/{record}/edit'),
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
