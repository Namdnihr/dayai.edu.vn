<?php

namespace App\Filament\Resources\Assessments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssessmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('class_group_id')
                    ->label('Lớp học')
                    ->relationship('classGroup', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('title')
                    ->label('Tên bài đánh giá')
                    ->required(),
                Select::make('assessment_type')
                    ->label('Loại đánh giá')
                    ->options([
                        'entry' => 'Đầu vào',
                        'quiz' => 'Kiểm tra nhanh',
                        'project' => 'Dự án',
                        'final' => 'Cuối khóa',
                        'progress' => 'Tiến bộ',
                    ])
                    ->default('progress'),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã công bố',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft'),
                TextInput::make('max_score')
                    ->label('Điểm tối đa')
                    ->required()
                    ->numeric()
                    ->default(10),
                TextInput::make('weight_percent')
                    ->label('Tỷ trọng %')
                    ->numeric(),
                DateTimePicker::make('assessment_at')
                    ->label('Thời điểm đánh giá'),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
            ]);
    }
}
