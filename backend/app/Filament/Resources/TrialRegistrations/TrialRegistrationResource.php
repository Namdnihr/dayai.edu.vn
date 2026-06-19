<?php

namespace App\Filament\Resources\TrialRegistrations;

use App\Filament\Resources\TrialRegistrations\Pages\CreateTrialRegistration;
use App\Filament\Resources\TrialRegistrations\Pages\EditTrialRegistration;
use App\Filament\Resources\TrialRegistrations\Pages\ListTrialRegistrations;
use App\Filament\Resources\TrialRegistrations\Pages\ViewTrialRegistration;
use App\Filament\Resources\TrialRegistrations\Schemas\TrialRegistrationForm;
use App\Filament\Resources\TrialRegistrations\Schemas\TrialRegistrationInfolist;
use App\Filament\Resources\TrialRegistrations\Tables\TrialRegistrationsTable;
use App\Models\TrialRegistration;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class TrialRegistrationResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = TrialRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    protected static ?string $navigationLabel = 'ÄÄƒng kÃ½ há»c thá»­';

    protected static ?string $modelLabel = 'Ä‘Äƒng kÃ½ há»c thá»­';

    protected static ?string $pluralModelLabel = 'Ä‘Äƒng kÃ½ há»c thá»­';

    protected static string|UnitEnum|null $navigationGroup = 'Váº­n hÃ nh trung tÃ¢m';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return TrialRegistrationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TrialRegistrationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrialRegistrationsTable::configure($table);
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
            'index' => ListTrialRegistrations::route('/'),
            'create' => CreateTrialRegistration::route('/create'),
            'view' => ViewTrialRegistration::route('/{record}'),
            'edit' => EditTrialRegistration::route('/{record}/edit'),
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
