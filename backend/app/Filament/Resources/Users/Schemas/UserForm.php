<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
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
                Select::make('person_id')
                    ->label('Hồ sơ cá nhân liên kết')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Tên hiển thị')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('email')
                    ->label('Email đăng nhập')
                    ->email()
                    ->maxLength(255)
                    ->required(),
                TextInput::make('phone')
                    ->label('Số điện thoại')
                    ->maxLength(30)
                    ->tel(),
                TextInput::make('password')
                    ->label('Mật khẩu')
                    ->helperText('Khi sửa tài khoản, để trống nếu không đổi mật khẩu.')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state)),
                Select::make('roles')
                    ->label('Vai trò')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'active' => 'Đang hoạt động',
                        'inactive' => 'Tạm ngưng',
                        'locked' => 'Đã khóa',
                    ])
                    ->default('active'),
                DateTimePicker::make('email_verified_at')
                    ->label('Thời điểm xác thực email'),
                DateTimePicker::make('last_login_at')
                    ->label('Lần đăng nhập gần nhất')
                    ->disabled()
                    ->dehydrated(false),
            ]);
    }
}
