<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
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
                TextInput::make('subtitle')
                    ->label('Tiêu đề phụ')
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                TextInput::make('course_code')
                    ->label('Mã khóa')
                    ->required(),
                Select::make('audience_type')
                    ->label('Đối tượng')
                    ->options([
                        'kids' => 'AI Kids',
                        'student' => 'AI Student',
                        'work' => 'AI Work',
                        'business' => 'AI Business',
                        'enterprise' => 'AI Enterprise',
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
                Select::make('learning_format')
                    ->label('Hình thức học')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                        'hybrid' => 'Kết hợp',
                        'in_house' => 'Đào tạo nội bộ',
                    ])
                    ->default('hybrid'),
                Textarea::make('short_description')
                    ->label('Mô tả ngắn')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Mô tả chi tiết')
                    ->columnSpanFull(),
                TagsInput::make('outcomes')
                    ->label('Kết quả học viên đạt được')
                    ->columnSpanFull(),
                TagsInput::make('who_should_join')
                    ->label('Ai nên tham gia')
                    ->columnSpanFull(),
                TagsInput::make('prerequisites')
                    ->label('Điều kiện đầu vào')
                    ->columnSpanFull(),
                TagsInput::make('tools_covered')
                    ->label('Công cụ AI được học')
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
                TextInput::make('price_label')
                    ->label('Nhãn giá hiển thị')
                    ->placeholder('Ví dụ: Liên hệ tư vấn / 3.500.000đ')
                    ->maxLength(255),
                TextInput::make('thumbnail_url')
                    ->label('Ảnh thẻ khóa học')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('hero_image_url')
                    ->label('Ảnh hero landing')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('primary_cta')
                    ->label('CTA chính')
                    ->default('Đăng ký tư vấn')
                    ->maxLength(255),
                Toggle::make('is_featured')
                    ->label('Khóa học nổi bật')
                    ->default(false),
                TextInput::make('sort_order')
                    ->label('Thứ tự hiển thị')
                    ->numeric()
                    ->default(0),
                TextInput::make('seo_title')
                    ->label('SEO title')
                    ->maxLength(255),
                Textarea::make('seo_description')
                    ->label('SEO description')
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
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
