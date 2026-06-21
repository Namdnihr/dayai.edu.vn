<?php

namespace App\Filament\Resources\Notifications;

use App\Filament\Resources\Notifications\Pages\CreateNotification;
use App\Filament\Resources\Notifications\Pages\EditNotification;
use App\Filament\Resources\Notifications\Pages\ListNotifications;
use App\Filament\Resources\Notifications\Pages\ViewNotification;
use App\Filament\Resources\Notifications\Schemas\NotificationForm;
use App\Filament\Resources\Notifications\Schemas\NotificationInfolist;
use App\Filament\Resources\Notifications\Tables\NotificationsTable;
use App\Models\Notification;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class NotificationResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = Notification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BellAlert;

    protected static ?string $navigationLabel = 'Thông báo';

    protected static ?string $modelLabel = 'thông báo';

    protected static ?string $pluralModelLabel = 'thông báo';

    protected static string|UnitEnum|null $navigationGroup = 'Nội dung';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return NotificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return NotificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationsTable::configure($table);
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
            'index' => ListNotifications::route('/'),
            'create' => CreateNotification::route('/create'),
            'view' => ViewNotification::route('/{record}'),
            'edit' => EditNotification::route('/{record}/edit'),
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
