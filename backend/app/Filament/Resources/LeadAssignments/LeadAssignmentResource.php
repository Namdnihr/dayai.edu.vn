<?php

namespace App\Filament\Resources\LeadAssignments;

use App\Filament\Resources\LeadAssignments\Pages\CreateLeadAssignment;
use App\Filament\Resources\LeadAssignments\Pages\EditLeadAssignment;
use App\Filament\Resources\LeadAssignments\Pages\ListLeadAssignments;
use App\Filament\Resources\LeadAssignments\Pages\ViewLeadAssignment;
use App\Filament\Resources\LeadAssignments\Schemas\LeadAssignmentForm;
use App\Filament\Resources\LeadAssignments\Schemas\LeadAssignmentInfolist;
use App\Filament\Resources\LeadAssignments\Tables\LeadAssignmentsTable;
use App\Models\LeadAssignment;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LeadAssignmentResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = LeadAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $navigationLabel = 'Phân công lead';

    protected static ?string $modelLabel = 'phân công lead';

    protected static ?string $pluralModelLabel = 'phân công lead';

    protected static string|UnitEnum|null $navigationGroup = 'Vận hành trung tâm';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return LeadAssignmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LeadAssignmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadAssignmentsTable::configure($table);
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
            'index' => ListLeadAssignments::route('/'),
            'create' => CreateLeadAssignment::route('/create'),
            'view' => ViewLeadAssignment::route('/{record}'),
            'edit' => EditLeadAssignment::route('/{record}/edit'),
        ];
    }
}
