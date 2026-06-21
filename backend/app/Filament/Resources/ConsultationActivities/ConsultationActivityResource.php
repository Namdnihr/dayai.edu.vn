<?php

namespace App\Filament\Resources\ConsultationActivities;

use App\Filament\Resources\ConsultationActivities\Pages\CreateConsultationActivity;
use App\Filament\Resources\ConsultationActivities\Pages\EditConsultationActivity;
use App\Filament\Resources\ConsultationActivities\Pages\ListConsultationActivities;
use App\Filament\Resources\ConsultationActivities\Pages\ViewConsultationActivity;
use App\Filament\Resources\ConsultationActivities\Schemas\ConsultationActivityForm;
use App\Filament\Resources\ConsultationActivities\Schemas\ConsultationActivityInfolist;
use App\Filament\Resources\ConsultationActivities\Tables\ConsultationActivitiesTable;
use App\Models\ConsultationActivity;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ConsultationActivityResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = ConsultationActivity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Phone;

    protected static ?string $navigationLabel = 'Lịch sử tư vấn';

    protected static ?string $modelLabel = 'lịch sử tư vấn';

    protected static ?string $pluralModelLabel = 'lịch sử tư vấn';

    protected static string|UnitEnum|null $navigationGroup = 'Vận hành trung tâm';

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return ConsultationActivityForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ConsultationActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ConsultationActivitiesTable::configure($table);
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
            'index' => ListConsultationActivities::route('/'),
            'create' => CreateConsultationActivity::route('/create'),
            'view' => ViewConsultationActivity::route('/{record}'),
            'edit' => EditConsultationActivity::route('/{record}/edit'),
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
