<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->default(fn () => Tenant::query()->value('id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('branch_id')
                    ->label('Cơ sở')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('customer_account_id')
                    ->label('Khách thanh toán')
                    ->relationship('customerAccount', 'display_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('lead_id')
                    ->label('Lead liên quan')
                    ->relationship('lead', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('order_code')
                    ->label('Mã đơn')
                    ->required(),
                Select::make('order_type')
                    ->label('Loại đơn')
                    ->options([
                        'b2c' => 'B2C',
                        'b2b' => 'B2B',
                        'trial_to_paid' => 'Học thử chuyển phí',
                        'renewal' => 'Gia hạn',
                    ])
                    ->default('b2c'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'confirmed' => 'Đã xác nhận',
                        'partially_paid' => 'Thanh toán một phần',
                        'paid' => 'Đã thanh toán',
                        'cancelled' => 'Đã hủy',
                        'refunded' => 'Đã hoàn tiền',
                    ])
                    ->default('draft'),
                DateTimePicker::make('ordered_at')
                    ->label('Ngày đặt')
                    ->default(now()),
                TextInput::make('subtotal_vnd')
                    ->label('Tạm tính')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount_vnd')
                    ->label('Giảm giá')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_vnd')
                    ->label('Tổng tiền')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('paid_vnd')
                    ->label('Đã thu')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('balance_vnd')
                    ->label('Còn lại')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
                Select::make('created_by_id')
                    ->label('Người tạo')
                    ->relationship('createdBy', 'name'),
            ]);
    }
}
