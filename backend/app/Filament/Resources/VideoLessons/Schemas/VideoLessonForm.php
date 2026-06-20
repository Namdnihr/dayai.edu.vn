<?php

namespace App\Filament\Resources\VideoLessons\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VideoLessonForm
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
                Select::make('content_item_id')
                    ->label('Bài viết liên quan')
                    ->relationship('contentItem', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->label('Khóa học liên quan')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('course_module_id')
                    ->label('Module khóa học')
                    ->relationship('courseModule', 'title')
                    ->searchable()
                    ->preload(),
                TextInput::make('sort_order')
                    ->label('Thứ tự bài học')
                    ->numeric()
                    ->default(0),
                TextInput::make('title')
                    ->label('Tiêu đề video')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft')
                    ->required(),
                Select::make('video_provider')
                    ->label('Nền tảng video')
                    ->options([
                        'youtube' => 'YouTube',
                        'vimeo' => 'Vimeo',
                        'internal' => 'Nội bộ',
                        'other' => 'Khác',
                    ])
                    ->default('youtube')
                    ->required(),
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('duration_minutes')
                    ->label('Thời lượng phút')
                    ->numeric(),
                Select::make('access_level')
                    ->label('Quyền xem')
                    ->options([
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi thông tin lead',
                        'student' => 'Chỉ học viên',
                        'internal' => 'Nội bộ',
                    ])
                    ->default('public')
                    ->required(),
                Textarea::make('summary')
                    ->label('Tóm tắt')
                    ->columnSpanFull(),
                TextInput::make('seo_title')
                    ->label('SEO title')
                    ->maxLength(255),
                Textarea::make('seo_description')
                    ->label('SEO description')
                    ->maxLength(500)
                    ->columnSpanFull(),
                TextInput::make('thumbnail_url')
                    ->label('Thumbnail URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('canonical_url')
                    ->label('Canonical URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Ngày xuất bản'),
            ]);
    }
}
