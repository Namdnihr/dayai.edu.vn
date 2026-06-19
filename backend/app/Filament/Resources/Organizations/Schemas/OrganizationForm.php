<?php

namespace App\Filament\Resources\Organizations\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrganizationForm
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
                    ->label('Cơ sở phụ trách')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Tên doanh nghiệp')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('short_name')
                    ->label('Tên viết tắt')
                    ->maxLength(100),
                Select::make('organization_type')
                    ->label('Loại tổ chức')
                    ->options([
                        'company' => 'Công ty',
                        'school' => 'Trường học',
                        'partner' => 'Đối tác',
                        'vendor' => 'Nhà cung cấp',
                    ])
                    ->required()
                    ->default('company'),
                TextInput::make('tax_code')
                    ->label('Mã số thuế')
                    ->maxLength(50),
                TextInput::make('industry')
                    ->label('Ngành nghề')
                    ->maxLength(100),
                Select::make('company_size')
                    ->label('Quy mô')
                    ->options([
                        '1-10' => '1-10 nhân sự',
                        '11-50' => '11-50 nhân sự',
                        '51-200' => '51-200 nhân sự',
                        '201-500' => '201-500 nhân sự',
                        '500+' => 'Trên 500 nhân sự',
                    ]),
                TextInput::make('phone')
                    ->label('SĐT')
                    ->maxLength(30)
                    ->tel(),
                TextInput::make('email')
                    ->label('Email')
                    ->maxLength(255)
                    ->email(),
                TextInput::make('website')
                    ->label('Website')
                    ->maxLength(255)
                    ->url(),
                Textarea::make('billing_address')
                    ->label('Địa chỉ xuất hóa đơn')
                    ->columnSpanFull(),
                Textarea::make('address')
                    ->label('Địa chỉ liên hệ')
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'prospect' => 'Tiềm năng',
                        'active' => 'Đang hợp tác',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->default('prospect'),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
