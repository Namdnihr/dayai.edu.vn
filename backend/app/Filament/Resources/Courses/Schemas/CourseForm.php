<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CourseForm
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
                    ->label('Tên khóa học')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                TextInput::make('course_code')
                    ->label('Mã khóa')
                    ->required(),
                Select::make('audience_type')
                    ->label('Đối tượng')
                    ->options([
                        'children' => 'Trẻ em',
                        'students' => 'Sinh viên',
                        'business_owners' => 'Chủ doanh nghiệp',
                        'companies' => 'Doanh nghiệp',
                        'mixed' => 'Tổng hợp',
                    ])
                    ->default('mixed'),
                Select::make('level')
                    ->label('Trình độ')
                    ->options([
                        'beginner' => 'Mới bắt đầu',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                    ])
                    ->default('beginner'),
                Textarea::make('short_description')
                    ->label('Mô tả ngắn')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Mô tả chi tiết')
                    ->columnSpanFull(),
                TextInput::make('duration_hours')
                    ->label('Thời lượng giờ')
                    ->numeric(),
                TextInput::make('default_session_count')
                    ->label('Số buổi mặc định')
                    ->numeric(),
                TextInput::make('default_price_vnd')
                    ->label('Giá mặc định VND')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft'),
            ]);
    }
}
