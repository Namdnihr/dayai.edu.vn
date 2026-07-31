<?php

namespace App\Filament\Resources\AffiliatePartners;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AffiliatePartners\Pages\CreateAffiliatePartner;
use App\Filament\Resources\AffiliatePartners\Pages\EditAffiliatePartner;
use App\Filament\Resources\AffiliatePartners\Pages\ListAffiliatePartners;
use App\Models\AffiliatePartner;
use App\Models\Tenant;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AffiliatePartnerResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = AffiliatePartner::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;
    protected static ?string $navigationLabel = 'Đối tác affiliate';
    protected static ?string $modelLabel = 'đối tác affiliate';
    protected static ?string $pluralModelLabel = 'đối tác affiliate';
    protected static string|UnitEnum|null $navigationGroup = 'Affiliate';
    protected static ?int $navigationSort = 31;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')->label('Đơn vị')->relationship('tenant', 'name')->default(fn () => Tenant::query()->value('id'))->required()->searchable()->preload(),
            TextInput::make('name')->label('Tên đối tác')->required()->maxLength(255),
            TextInput::make('code')->label('Mã affiliate')->required()->maxLength(255),
            Select::make('partner_type')->label('Loại đối tác')->options(['individual' => 'Cá nhân', 'company' => 'Doanh nghiệp', 'creator' => 'Creator', 'school' => 'Trường/CLB'])->default('individual')->required(),
            TextInput::make('default_commission_percent')->label('% hoa hồng mặc định')->numeric()->default(10)->required(),
            Select::make('person_id')->label('Cá nhân liên kết')->relationship('person', 'full_name')->searchable()->preload(),
            Select::make('organization_id')->label('Doanh nghiệp liên kết')->relationship('organization', 'name')->searchable()->preload(),
            Select::make('status')->label('Trạng thái')->options(['active' => 'Đang hoạt động', 'paused' => 'Tạm dừng', 'inactive' => 'Ngưng'])->default('active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Đối tác')->searchable()->sortable(),
            TextColumn::make('code')->label('Mã')->searchable()->copyable(),
            TextColumn::make('partner_type')->label('Loại')->badge(),
            TextColumn::make('default_commission_percent')->label('% HH')->suffix('%')->sortable(),
            TextColumn::make('commissions_count')->label('Commission')->counts('commissions')->sortable(),
            TextColumn::make('commissions_sum_commission_vnd')->label('Tổng HH')->sum('commissions', 'commission_vnd')->money('VND')->sortable(),
            TextColumn::make('status')->label('Trạng thái')->badge(),
            TextColumn::make('created_at')->label('Ngày tạo')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->filters([
            SelectFilter::make('status')->label('Trạng thái')->options(['active' => 'Đang hoạt động', 'paused' => 'Tạm dừng', 'inactive' => 'Ngưng']),
        ])->recordActions([EditAction::make()])->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListAffiliatePartners::route('/'), 'create' => CreateAffiliatePartner::route('/create'), 'edit' => EditAffiliatePartner::route('/{record}/edit')];
    }
}
