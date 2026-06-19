<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
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
                Select::make('invoice_id')
                    ->label('Hóa đơn')
                    ->relationship('invoice', 'invoice_code')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('order_id')
                    ->label('Đơn hàng')
                    ->relationship('order', 'order_code')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('customer_account_id')
                    ->label('Khách thanh toán')
                    ->relationship('customerAccount', 'display_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('payment_code')
                    ->label('Mã thanh toán')
                    ->required(),
                Select::make('payment_method')
                    ->label('Phương thức')
                    ->options([
                        'cash' => 'Tiền mặt',
                        'bank_transfer' => 'Chuyển khoản',
                        'card' => 'Thẻ',
                        'momo' => 'Momo',
                        'vnpay' => 'VNPay',
                        'other' => 'Khác',
                    ])
                    ->default('bank_transfer'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xử lý',
                        'completed' => 'Hoàn tất',
                        'failed' => 'Thất bại',
                        'refunded' => 'Đã hoàn',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->default('completed'),
                TextInput::make('amount_vnd')
                    ->label('Số tiền')
                    ->required()
                    ->numeric()
                    ->default(0),
                DateTimePicker::make('paid_at')
                    ->label('Ngày thanh toán')
                    ->default(now())
                    ->required(),
                TextInput::make('reference_no')
                    ->label('Mã tham chiếu'),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
                Select::make('created_by_id')
                    ->label('Người tạo')
                    ->relationship('createdBy', 'name'),
            ]);
    }
}
