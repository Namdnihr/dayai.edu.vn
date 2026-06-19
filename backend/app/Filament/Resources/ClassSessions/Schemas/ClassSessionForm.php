<?php

namespace App\Filament\Resources\ClassSessions\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClassSessionForm
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
                Select::make('class_group_id')
                    ->label('Lớp học')
                    ->relationship('classGroup', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('course_module_id')
                    ->label('Module')
                    ->relationship('courseModule', 'title')
                    ->searchable()
                    ->preload(),
                Select::make('teacher_profile_id')
                    ->label('Giảng viên')
                    ->relationship('teacherProfile', 'teacher_code')
                    ->searchable()
                    ->preload(),
                TextInput::make('session_no')
                    ->label('Số buổi')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->label('Tiêu đề'),
                DateTimePicker::make('starts_at')
                    ->label('Bắt đầu')
                    ->required(),
                DateTimePicker::make('ends_at')
                    ->label('Kết thúc')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'scheduled' => 'Đã lên lịch',
                        'completed' => 'Đã học',
                        'cancelled' => 'Đã hủy',
                        'makeup' => 'Học bù',
                    ])
                    ->default('scheduled'),
                TextInput::make('location')
                    ->label('Địa điểm/link học'),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
