<?php

namespace App\Filament\Resources\ProgressReports;

use App\Filament\Resources\ProgressReports\Pages\CreateProgressReport;
use App\Filament\Resources\ProgressReports\Pages\EditProgressReport;
use App\Filament\Resources\ProgressReports\Pages\ListProgressReports;
use App\Filament\Resources\ProgressReports\Pages\ViewProgressReport;
use App\Filament\Resources\ProgressReports\Schemas\ProgressReportForm;
use App\Filament\Resources\ProgressReports\Schemas\ProgressReportInfolist;
use App\Filament\Resources\ProgressReports\Tables\ProgressReportsTable;
use App\Models\ProgressReport;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ProgressReportResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = ProgressReport::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentChartBar;

    protected static ?string $navigationLabel = 'Báo cáo tiến bộ';

    protected static ?string $modelLabel = 'báo cáo tiến bộ';

    protected static ?string $pluralModelLabel = 'báo cáo tiến bộ';

    protected static string|UnitEnum|null $navigationGroup = 'Đào tạo & LMS';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return ProgressReportForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProgressReportInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProgressReportsTable::configure($table);
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
            'index' => ListProgressReports::route('/'),
            'create' => CreateProgressReport::route('/create'),
            'view' => ViewProgressReport::route('/{record}'),
            'edit' => EditProgressReport::route('/{record}/edit'),
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
