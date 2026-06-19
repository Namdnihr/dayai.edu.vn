<?php

namespace App\Filament\Resources\ClassGroups\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ClassGroupForm
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
                Select::make('branch_id')
                    ->label('Cơ sở')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('course_id')
                    ->label('Khóa học')
                    ->relationship('course', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('teacher_profile_id')
                    ->label('Giảng viên chính')
                    ->relationship('teacherProfile', 'teacher_code')
                    ->searchable()
                    ->preload(),
                TextInput::make('class_code')
                    ->label('Mã lớp')
                    ->required(),
                TextInput::make('name')
                    ->label('Tên lớp')
                    ->required(),
                Select::make('learning_format')
                    ->label('Hình thức học')
                    ->options([
                        'offline' => 'Offline',
                        'online' => 'Online',
                        'hybrid' => 'Hybrid',
                        'in_company' => 'Tại doanh nghiệp',
                    ])
                    ->default('offline'),
                DatePicker::make('start_date')
                    ->label('Ngày bắt đầu'),
                DatePicker::make('end_date')
                    ->label('Ngày kết thúc'),
                TextInput::make('max_students')
                    ->label('Sĩ số tối đa')
                    ->numeric(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'planned' => 'Dự kiến',
                        'enrolling' => 'Đang tuyển sinh',
                        'active' => 'Đang học',
                        'completed' => 'Hoàn tất',
                        'paused' => 'Tạm dừng',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->default('planned'),
                Textarea::make('schedule_note')
                    ->label('Ghi chú lịch học')
                    ->columnSpanFull(),
                TextInput::make('location')
                    ->label('Địa điểm/link học'),
                Select::make('organization_id')
                    ->label('Doanh nghiệp')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
