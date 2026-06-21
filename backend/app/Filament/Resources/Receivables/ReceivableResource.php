<?php

namespace App\Filament\Resources\Receivables;

use App\Filament\Resources\Receivables\Pages\CreateReceivable;
use App\Filament\Resources\Receivables\Pages\EditReceivable;
use App\Filament\Resources\Receivables\Pages\ListReceivables;
use App\Filament\Resources\Receivables\Pages\ViewReceivable;
use App\Filament\Resources\Receivables\Schemas\ReceivableForm;
use App\Filament\Resources\Receivables\Schemas\ReceivableInfolist;
use App\Filament\Resources\Receivables\Tables\ReceivablesTable;
use App\Models\Receivable;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class ReceivableResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = Receivable::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calculator;

    protected static ?string $navigationLabel = 'Công nợ';

    protected static ?string $modelLabel = 'công nợ';

    protected static ?string $pluralModelLabel = 'công nợ';

    protected static string|UnitEnum|null $navigationGroup = 'Tài chính';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return ReceivableForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReceivableInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceivablesTable::configure($table);
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
            'index' => ListReceivables::route('/'),
            'create' => CreateReceivable::route('/create'),
            'view' => ViewReceivable::route('/{record}'),
            'edit' => EditReceivable::route('/{record}/edit'),
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
