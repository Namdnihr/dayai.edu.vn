<?php

namespace App\Filament\Resources\StudentProfiles\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class StudentProfileForm
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
                Select::make('person_id')
                    ->label('Cá nhân')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('student_code')
                    ->label('Mã học viên')
                    ->required(),
                Select::make('student_type')
                    ->label('Loại học viên')
                    ->options([
                        'child' => 'Trẻ em',
                        'university_student' => 'Sinh viên',
                        'working_professional' => 'Người đi làm',
                        'business_owner' => 'Chủ doanh nghiệp',
                        'company_employee' => 'Nhân sự công ty',
                    ])
                    ->required(),
                TextInput::make('current_school')
                    ->label('Trường hiện tại'),
                TextInput::make('current_company')
                    ->label('Công ty hiện tại'),
                TextInput::make('job_title')
                    ->label('Chức danh'),
                Select::make('organization_id')
                    ->label('Doanh nghiệp')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('learning_goal')
                    ->label('Mục tiêu học')
                    ->columnSpanFull(),
                Select::make('entry_level')
                    ->label('Trình độ đầu vào')
                    ->options([
                        'beginner' => 'Mới bắt đầu',
                        'basic' => 'Cơ bản',
                        'intermediate' => 'Trung cấp',
                        'advanced' => 'Nâng cao',
                    ]),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'lead_converted' => 'Từ lead chuyển đổi',
                        'active' => 'Đang học',
                        'inactive' => 'Tạm ngưng',
                    ])
                    ->default('lead_converted'),
            ]);
    }
}
