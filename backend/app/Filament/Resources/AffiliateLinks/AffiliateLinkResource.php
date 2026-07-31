<?php

namespace App\Filament\Resources\AffiliateLinks;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AffiliateLinks\Pages\CreateAffiliateLink;
use App\Filament\Resources\AffiliateLinks\Pages\EditAffiliateLink;
use App\Filament\Resources\AffiliateLinks\Pages\ListAffiliateLinks;
use App\Models\AffiliateLink;
use App\Models\Tenant;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AffiliateLinkResource extends Resource
{
    use AuthorizesResourceAccess;
    protected static ?string $model = AffiliateLink::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Link;
    protected static ?string $navigationLabel = 'Link affiliate';
    protected static ?string $modelLabel = 'link affiliate';
    protected static ?string $pluralModelLabel = 'link affiliate';
    protected static string|UnitEnum|null $navigationGroup = 'Affiliate';
    protected static ?int $navigationSort = 32;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')->label('Đơn vị')->relationship('tenant', 'name')->default(fn () => Tenant::query()->value('id'))->required()->searchable()->preload(),
            Select::make('affiliate_partner_id')->label('Đối tác')->relationship('partner', 'name')->required()->searchable()->preload(),
            Select::make('course_id')->label('Khóa học')->relationship('course', 'name')->searchable()->preload(),
            TextInput::make('code')->label('Mã referral/link')->required()->maxLength(255),
            TextInput::make('campaign')->label('Campaign')->maxLength(255),
            TextInput::make('target_url')->label('URL đích')->maxLength(1000),
            TextInput::make('commission_percent')->label('% hoa hồng riêng')->numeric(),
            Select::make('status')->label('Trạng thái')->options(['active' => 'Đang hoạt động', 'paused' => 'Tạm dừng', 'inactive' => 'Ngưng'])->default('active')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('code')->label('Mã')->searchable()->copyable(),
            TextColumn::make('partner.name')->label('Đối tác')->searchable(),
            TextColumn::make('course.name')->label('Khóa học')->toggleable(),
            TextColumn::make('campaign')->label('Campaign')->searchable(),
            TextColumn::make('commission_percent')->label('% HH')->suffix('%')->sortable(),
            TextColumn::make('clicks_count')->label('Click')->counts('clicks')->sortable(),
            TextColumn::make('commissions_sum_commission_vnd')->label('HH')->sum('commissions', 'commission_vnd')->money('VND')->sortable(),
            TextColumn::make('status')->label('Trạng thái')->badge(),
        ])->filters([
            SelectFilter::make('status')->label('Trạng thái')->options(['active' => 'Đang hoạt động', 'paused' => 'Tạm dừng', 'inactive' => 'Ngưng']),
        ])->recordActions([EditAction::make()])->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListAffiliateLinks::route('/'), 'create' => CreateAffiliateLink::route('/create'), 'edit' => EditAffiliateLink::route('/{record}/edit')];
    }
}
