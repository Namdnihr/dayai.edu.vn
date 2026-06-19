<?php

namespace App\Filament\Resources\CustomerAccounts\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerAccountForm
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
                TextInput::make('customer_code')
                    ->label('Mã khách hàng')
                    ->required(),
                Select::make('account_type')
                    ->label('Loại khách thanh toán')
                    ->options([
                        'individual' => 'Cá nhân',
                        'organization' => 'Doanh nghiệp',
                    ])
                    ->required(),
                Select::make('person_id')
                    ->label('Cá nhân thanh toán')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                Select::make('organization_id')
                    ->label('Doanh nghiệp thanh toán')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('display_name')
                    ->label('Tên hiển thị')
                    ->required(),
                TextInput::make('phone')
                    ->label('SĐT')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                Textarea::make('billing_address')
                    ->label('Địa chỉ xuất hóa đơn')
                    ->columnSpanFull(),
                TextInput::make('tax_code')
                    ->label('Mã số thuế'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang hoạt động',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->default('active'),
            ]);
    }
}
