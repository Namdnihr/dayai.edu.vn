<?php

namespace App\Filament\Resources\AssessmentResults;

use App\Filament\Resources\AssessmentResults\Pages\CreateAssessmentResult;
use App\Filament\Resources\AssessmentResults\Pages\EditAssessmentResult;
use App\Filament\Resources\AssessmentResults\Pages\ListAssessmentResults;
use App\Filament\Resources\AssessmentResults\Pages\ViewAssessmentResult;
use App\Filament\Resources\AssessmentResults\Schemas\AssessmentResultForm;
use App\Filament\Resources\AssessmentResults\Schemas\AssessmentResultInfolist;
use App\Filament\Resources\AssessmentResults\Tables\AssessmentResultsTable;
use App\Models\AssessmentResult;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class AssessmentResultResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = AssessmentResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static ?string $navigationLabel = 'Kết quả đánh giá';

    protected static ?string $modelLabel = 'kết quả đánh giá';

    protected static ?string $pluralModelLabel = 'kết quả đánh giá';

    protected static string|UnitEnum|null $navigationGroup = 'LMS & Khóa học';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return AssessmentResultForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AssessmentResultInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AssessmentResultsTable::configure($table);
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
            'index' => ListAssessmentResults::route('/'),
            'create' => CreateAssessmentResult::route('/create'),
            'view' => ViewAssessmentResult::route('/{record}'),
            'edit' => EditAssessmentResult::route('/{record}/edit'),
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
