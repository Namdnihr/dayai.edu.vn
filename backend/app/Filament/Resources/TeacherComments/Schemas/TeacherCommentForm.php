<?php

namespace App\Filament\Resources\TeacherComments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TeacherCommentForm
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
                Select::make('class_session_id')
                    ->label('Buổi học')
                    ->relationship('classSession', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('teacher_profile_id')
                    ->label('Giáo viên')
                    ->relationship('teacherProfile', 'teacher_code')
                    ->searchable()
                    ->preload(),
                Select::make('comment_type')
                    ->label('Loại nhận xét')
                    ->options([
                        'session' => 'Theo buổi học',
                        'progress' => 'Tiến bộ',
                        'behavior' => 'Thái độ',
                        'homework' => 'Bài tập',
                        'general' => 'Chung',
                    ])
                    ->default('progress'),
                Select::make('visibility')
                    ->label('Hiển thị')
                    ->options([
                        'internal' => 'Nội bộ',
                        'guardian' => 'Phụ huynh',
                        'student' => 'Học viên',
                    ])
                    ->default('guardian'),
                TextInput::make('title')
                    ->label('Tiêu đề'),
                Textarea::make('comment')
                    ->label('Nội dung nhận xét')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('rating')
                    ->label('Đánh giá 1-5')
                    ->numeric(),
                DateTimePicker::make('commented_at')
                    ->label('Thời điểm nhận xét'),
            ]);
    }
}
