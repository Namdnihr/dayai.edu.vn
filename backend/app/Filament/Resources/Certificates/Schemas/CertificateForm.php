<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CertificateForm
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
                TextInput::make('certificate_code')
                    ->label('Mã chứng chỉ')
                    ->required(),
                TextInput::make('verification_token')
                    ->label('Mã xác thực')
                    ->required(),
                TextInput::make('title')
                    ->label('Tên chứng chỉ')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'issued' => 'Đã cấp',
                        'revoked' => 'Đã thu hồi',
                    ])
                    ->default('draft'),
                TextInput::make('final_score')
                    ->label('Điểm cuối khóa')
                    ->numeric(),
                TextInput::make('grade')
                    ->label('Xếp loại'),
                DateTimePicker::make('issued_at')
                    ->label('Ngày cấp'),
                DatePicker::make('expires_at')
                    ->label('Ngày hết hạn'),
                TextInput::make('file_path')
                    ->label('Đường dẫn file'),
                Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
