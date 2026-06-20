<?php

namespace App\Filament\Resources\AffiliateCommissions;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AffiliateCommissions\Pages\EditAffiliateCommission;
use App\Filament\Resources\AffiliateCommissions\Pages\ListAffiliateCommissions;
use App\Models\AffiliateCommission;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class AffiliateCommissionResource extends Resource
{
    use AuthorizesResourceAccess;
    protected static ?string $model = AffiliateCommission::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Banknotes;
    protected static ?string $navigationLabel = 'Hoa hồng affiliate';
    protected static ?string $modelLabel = 'hoa hồng affiliate';
    protected static ?string $pluralModelLabel = 'hoa hồng affiliate';
    protected static string|UnitEnum|null $navigationGroup = 'Tài chính';
    protected static ?int $navigationSort = 75;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')->label('Trạng thái')->options(['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'paid' => 'Đã trả', 'rejected' => 'Từ chối'])->required(),
            DateTimePicker::make('approved_at')->label('Ngày duyệt'),
            DateTimePicker::make('paid_at')->label('Ngày trả'),
            Textarea::make('notes')->label('Ghi chú')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('partner.name')->label('Đối tác')->searchable(),
            TextColumn::make('order.order_code')->label('Đơn hàng')->searchable(),
            TextColumn::make('lead.full_name')->label('Lead')->searchable(),
            TextColumn::make('affiliate_code')->label('Aff code')->searchable(),
            TextColumn::make('order_total_vnd')->label('Doanh số')->money('VND')->sortable(),
            TextColumn::make('commission_percent')->label('%')->suffix('%')->sortable(),
            TextColumn::make('commission_vnd')->label('Hoa hồng')->money('VND')->sortable(),
            TextColumn::make('status')->label('Trạng thái')->badge(),
            TextColumn::make('created_at')->label('Ngày tạo')->dateTime()->sortable(),
        ])->filters([
            SelectFilter::make('status')->label('Trạng thái')->options(['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'paid' => 'Đã trả', 'rejected' => 'Từ chối']),
        ])->recordActions([EditAction::make()])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListAffiliateCommissions::route('/'), 'edit' => EditAffiliateCommission::route('/{record}/edit')];
    }
}
