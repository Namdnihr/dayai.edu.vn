<?php

namespace App\Filament\Resources\Branches\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BranchForm
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
                TextInput::make('name')
                    ->label('Tên cơ sở')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('code')
                    ->label('Mã cơ sở')
                    ->maxLength(50)
                    ->required(),
                TextInput::make('phone')
                    ->label('SĐT')
                    ->maxLength(30)
                    ->tel(),
                TextInput::make('email')
                    ->label('Email')
                    ->maxLength(255)
                    ->email(),
                Textarea::make('address')
                    ->label('Địa chỉ')
                    ->columnSpanFull(),
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
