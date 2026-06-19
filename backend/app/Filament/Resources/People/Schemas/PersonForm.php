<?php

namespace App\Filament\Resources\People\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PersonForm
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
                TextInput::make('full_name')
                    ->label('Họ và tên')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('display_name')
                    ->label('Tên gọi')
                    ->maxLength(255),
                Select::make('gender')
                    ->label('Giới tính')
                    ->options([
                        'male' => 'Nam',
                        'female' => 'Nữ',
                        'other' => 'Khác',
                    ]),
                DatePicker::make('date_of_birth')
                    ->label('Ngày sinh'),
                TextInput::make('phone')
                    ->label('SĐT chính')
                    ->maxLength(30)
                    ->tel(),
                TextInput::make('secondary_phone')
                    ->label('SĐT phụ')
                    ->maxLength(30)
                    ->tel(),
                TextInput::make('email')
                    ->label('Email')
                    ->maxLength(255)
                    ->email(),
                Textarea::make('address')
                    ->label('Địa chỉ')
                    ->columnSpanFull(),
                TextInput::make('province')
                    ->label('Tỉnh/thành')
                    ->maxLength(100),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
