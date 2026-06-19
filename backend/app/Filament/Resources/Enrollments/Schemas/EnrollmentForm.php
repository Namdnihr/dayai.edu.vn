<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EnrollmentForm
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
                Select::make('student_profile_id')
                    ->label('Học viên')
                    ->relationship('studentProfile', 'student_code')
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
                TextInput::make('order_id'),
                TextInput::make('enrollment_code')
                    ->label('Mã xếp lớp')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ xếp lớp',
                        'active' => 'Đang học',
                        'completed' => 'Hoàn tất',
                        'paused' => 'Tạm dừng',
                        'cancelled' => 'Đã hủy',
                        'transferred' => 'Chuyển lớp',
                    ])
                    ->default('active'),
                DateTimePicker::make('enrolled_at')
                    ->label('Ngày ghi danh')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('started_at')
                    ->label('Ngày bắt đầu'),
                DateTimePicker::make('completed_at')
                    ->label('Ngày hoàn tất'),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
