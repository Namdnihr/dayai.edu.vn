<?php

namespace App\Filament\Resources\ProgressReports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProgressReportForm
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
                Select::make('student_profile_id')
                    ->label('Học viên')
                    ->relationship('studentProfile', 'student_code')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('enrollment_id')
                    ->label('Ghi danh')
                    ->relationship('enrollment', 'enrollment_code')
                    ->searchable()
                    ->preload(),
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
                Select::make('teacher_profile_id')
                    ->label('Giáo viên')
                    ->relationship('teacherProfile', 'teacher_code')
                    ->searchable()
                    ->preload(),
                Select::make('report_period')
                    ->label('Kỳ báo cáo')
                    ->options([
                        'weekly' => 'Hàng tuần',
                        'monthly' => 'Hàng tháng',
                        'course' => 'Theo khóa',
                        'phase' => 'Theo giai đoạn',
                    ])
                    ->default('weekly'),
                TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã công bố',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('draft'),
                Select::make('overall_level')
                    ->label('Mức tổng quan')
                    ->options([
                        'needs_support' => 'Cần hỗ trợ',
                        'on_track' => 'Đúng tiến độ',
                        'excellent' => 'Nổi bật',
                    ]),
                TextInput::make('progress_percent')
                    ->label('% tiến độ')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('strengths')
                    ->label('Điểm mạnh')
                    ->columnSpanFull(),
                Textarea::make('improvements')
                    ->label('Cần cải thiện')
                    ->columnSpanFull(),
                Textarea::make('recommendation')
                    ->label('Khuyến nghị')
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->label('Ngày công bố'),
            ]);
    }
}
