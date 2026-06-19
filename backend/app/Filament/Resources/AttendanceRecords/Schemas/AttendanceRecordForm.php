<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendanceRecordForm
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
                Select::make('class_session_id')
                    ->label('Buổi học')
                    ->relationship('classSession', 'title')
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
                    ->label('Enrollment')
                    ->relationship('enrollment', 'enrollment_code')
                    ->searchable()
                    ->preload(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'present' => 'Có mặt',
                        'absent' => 'Vắng',
                        'late' => 'Đi trễ',
                        'excused' => 'Nghỉ phép',
                        'makeup' => 'Học bù',
                    ])
                    ->default('present'),
                DateTimePicker::make('checked_in_at')
                    ->label('Giờ điểm danh'),
                TextInput::make('minutes_late')
                    ->label('Số phút trễ')
                    ->numeric(),
                Textarea::make('absence_reason')
                    ->label('Lý do vắng')
                    ->columnSpanFull(),
                Textarea::make('teacher_note')
                    ->label('Nhận xét giáo viên')
                    ->columnSpanFull(),
                Select::make('checked_by_id')
                    ->label('Người điểm danh')
                    ->relationship('checkedBy', 'name'),
            ]);
    }
}
