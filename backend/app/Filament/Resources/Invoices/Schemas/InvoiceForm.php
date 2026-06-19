<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvoiceForm
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
                TextInput::make('invoice_code')
                    ->label('Mã hóa đơn')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'issued' => 'Đã phát hành',
                        'partially_paid' => 'Thanh toán một phần',
                        'paid' => 'Đã thanh toán',
                        'overdue' => 'Quá hạn',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->default('issued'),
                DateTimePicker::make('issued_at')
                    ->label('Ngày phát hành')
                    ->default(now()),
                DatePicker::make('due_date')
                    ->label('Hạn thanh toán'),
                TextInput::make('amount_vnd')
                    ->label('Phải thu')
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
            ]);
    }
}
