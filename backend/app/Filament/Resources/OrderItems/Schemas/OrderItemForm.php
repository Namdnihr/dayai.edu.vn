<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderItemForm
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
                Select::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('class_group_id')
                    ->label('Lớp học')
                    ->relationship('classGroup', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('student_profile_id')
                    ->label('Học viên')
                    ->relationship('studentProfile', 'student_code')
                    ->searchable()
                    ->preload(),
                TextInput::make('description')
                    ->label('Nội dung')
                    ->required(),
                TextInput::make('quantity')
                    ->label('Số lượng')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('unit_price_vnd')
                    ->label('Đơn giá')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('discount_vnd')
                    ->label('Giảm giá')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('line_total_vnd')
                    ->label('Thành tiền')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
