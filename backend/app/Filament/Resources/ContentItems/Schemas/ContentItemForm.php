<?php

namespace App\Filament\Resources\ContentItems\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContentItemForm
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
                Select::make('content_category_id')
                    ->label('Danh mục')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->label('Khóa học liên quan')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('author_id')
                    ->label('Tác giả')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Select::make('content_type')
                    ->label('Loại nội dung')
                    ->options([
                        'article' => 'Bài viết',
                        'checklist' => 'Checklist',
                        'case_study' => 'Case study',
                        'prompt_library' => 'Prompt library',
                        'news' => 'Tin tức',
                    ])
                    ->default('article')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft')
                    ->required(),
                Textarea::make('excerpt')
                    ->label('Mô tả ngắn')
                    ->columnSpanFull(),
                Textarea::make('body')
                    ->label('Nội dung')
                    ->rows(10)
                    ->columnSpanFull(),
                TextInput::make('cover_image_url')
                    ->label('Ảnh đại diện URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
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
                TextInput::make('og_image_url')
                    ->label('Ảnh chia sẻ mạng xã hội')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Select::make('expertise_level')
                    ->label('Mức độ chuyên môn')
                    ->options([
                        'beginner' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                        'expert' => 'Chuyên gia',
                    ])
                    ->default('beginner')
                    ->required(),
                TextInput::make('reviewed_by')
                    ->label('Người kiểm duyệt chuyên môn')
                    ->maxLength(255),
                DatePicker::make('reviewed_at')
                    ->label('Ngày kiểm duyệt'),
                TagsInput::make('references')
                    ->label('Nguồn tham khảo / dẫn chứng')
                    ->columnSpanFull(),
                TagsInput::make('tags')
                    ->label('Tags')
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Ngày xuất bản'),
            ]);
    }
}
