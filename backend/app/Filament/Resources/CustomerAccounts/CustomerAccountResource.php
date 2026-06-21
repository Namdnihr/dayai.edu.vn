<?php

namespace App\Filament\Resources\CustomerAccounts;

use App\Filament\Resources\CustomerAccounts\Pages\CreateCustomerAccount;
use App\Filament\Resources\CustomerAccounts\Pages\EditCustomerAccount;
use App\Filament\Resources\CustomerAccounts\Pages\ListCustomerAccounts;
use App\Filament\Resources\CustomerAccounts\Pages\ViewCustomerAccount;
use App\Filament\Resources\CustomerAccounts\Schemas\CustomerAccountForm;
use App\Filament\Resources\CustomerAccounts\Schemas\CustomerAccountInfolist;
use App\Filament\Resources\CustomerAccounts\Tables\CustomerAccountsTable;
use App\Models\CustomerAccount;
use BackedEnum;
use App\Filament\Concerns\AuthorizesResourceAccess;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class CustomerAccountResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = CustomerAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CreditCard;

    protected static ?string $navigationLabel = 'Khách hàng thanh toán';

    protected static ?string $modelLabel = 'khách hàng thanh toán';

    protected static ?string $pluralModelLabel = 'khách hàng thanh toán';

    protected static string|UnitEnum|null $navigationGroup = 'Dữ liệu nền';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return CustomerAccountForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerAccountInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerAccountsTable::configure($table);
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
            'index' => ListCustomerAccounts::route('/'),
            'create' => CreateCustomerAccount::route('/create'),
            'view' => ViewCustomerAccount::route('/{record}'),
            'edit' => EditCustomerAccount::route('/{record}/edit'),
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
