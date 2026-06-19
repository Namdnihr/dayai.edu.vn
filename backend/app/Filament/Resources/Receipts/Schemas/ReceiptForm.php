<?php

namespace App\Filament\Resources\Receipts\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ReceiptForm
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
                Select::make('payment_id')
                    ->label('Thanh toán')
                    ->relationship('payment', 'payment_code')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('receipt_code')
                    ->label('Mã phiếu thu')
                    ->required(),
                DateTimePicker::make('issued_at')
                    ->label('Ngày phát hành')
                    ->default(now())
                    ->required(),
                Select::make('issued_by_id')
                    ->label('Người phát hành')
                    ->relationship('issuedBy', 'name'),
                TextInput::make('payer_name')
                    ->label('Người nộp')
                    ->required(),
                TextInput::make('amount_vnd')
                    ->label('Số tiền')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('content')
                    ->label('Nội dung')
                    ->columnSpanFull(),
            ]);
    }
}
