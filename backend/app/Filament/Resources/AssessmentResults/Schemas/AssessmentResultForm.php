<?php

namespace App\Filament\Resources\AssessmentResults\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AssessmentResultForm
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
                Select::make('assessment_id')
                    ->label('Bài đánh giá')
                    ->relationship('assessment', 'title')
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
                Select::make('teacher_profile_id')
                    ->label('Giáo viên')
                    ->relationship('teacherProfile', 'teacher_code')
                    ->searchable()
                    ->preload(),
                TextInput::make('score')
                    ->label('Điểm')
                    ->numeric(),
                TextInput::make('max_score')
                    ->label('Điểm tối đa')
                    ->required()
                    ->numeric()
                    ->default(10),
                Select::make('level')
                    ->label('Mức độ')
                    ->options([
                        'needs_support' => 'Cần hỗ trợ',
                        'on_track' => 'Đúng tiến độ',
                        'excellent' => 'Nổi bật',
                    ]),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'reviewed' => 'Đã duyệt',
                        'published' => 'Đã công bố',
                    ])
                    ->default('draft'),
                Textarea::make('feedback')
                    ->label('Nhận xét tổng quan')
                    ->columnSpanFull(),
                Textarea::make('strengths')
                    ->label('Điểm mạnh')
                    ->columnSpanFull(),
                Textarea::make('improvements')
                    ->label('Cần cải thiện')
                    ->columnSpanFull(),
                DateTimePicker::make('assessed_at')
                    ->label('Thời điểm chấm'),
            ]);
    }
}
