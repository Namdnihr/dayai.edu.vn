<?php

namespace App\Filament\Resources\Receivables\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReceivableForm
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
                Select::make('customer_account_id')
                    ->label('Khách thanh toán')
                    ->relationship('customerAccount', 'display_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'open' => 'Đang mở',
                        'partially_paid' => 'Thanh toán một phần',
                        'paid' => 'Đã thanh toán',
                        'overdue' => 'Quá hạn',
                        'written_off' => 'Xóa nợ',
                    ])
                    ->default('open'),
                TextInput::make('original_amount_vnd')
                    ->label('Số tiền gốc')
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
                DatePicker::make('due_date')
                    ->label('Hạn thanh toán'),
            ]);
    }
}
