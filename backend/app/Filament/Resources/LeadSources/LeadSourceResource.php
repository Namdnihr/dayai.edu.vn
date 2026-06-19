<?php

namespace App\Filament\Resources\LeadSources;

use App\Filament\Resources\LeadSources\Pages\CreateLeadSource;
use App\Filament\Resources\LeadSources\Pages\EditLeadSource;
use App\Filament\Resources\LeadSources\Pages\ListLeadSources;
use App\Filament\Resources\LeadSources\Pages\ViewLeadSource;
use App\Filament\Resources\LeadSources\Schemas\LeadSourceForm;
use App\Filament\Resources\LeadSources\Schemas\LeadSourceInfolist;
use App\Filament\Resources\LeadSources\Tables\LeadSourcesTable;
use App\Models\LeadSource;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LeadSourceResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = LeadSource::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CursorArrowRays;

    protected static ?string $navigationLabel = 'Nguá»“n lead';

    protected static ?string $modelLabel = 'nguá»“n lead';

    protected static ?string $pluralModelLabel = 'nguá»“n lead';

    protected static string|UnitEnum|null $navigationGroup = 'Váº­n hÃ nh trung tÃ¢m';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return LeadSourceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadSourceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadSourcesTable::configure($table);
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
            'index' => ListLeadSources::route('/'),
            'create' => CreateLeadSource::route('/create'),
            'view' => ViewLeadSource::route('/{record}'),
            'edit' => EditLeadSource::route('/{record}/edit'),
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
