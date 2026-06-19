<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NotificationForm
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
                Select::make('person_id')
                    ->label('Người nhận')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                Select::make('student_profile_id')
                    ->label('Học viên')
                    ->relationship('studentProfile', 'student_code')
                    ->searchable()
                    ->preload(),
                Select::make('organization_id')
                    ->label('Doanh nghiệp')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('audience_type')
                    ->label('Nhóm nhận')
                    ->options([
                        'student' => 'Học viên',
                        'guardian' => 'Phụ huynh',
                        'company' => 'Doanh nghiệp/HR',
                        'internal' => 'Nội bộ',
                    ])
                    ->default('student'),
                Select::make('notification_type')
                    ->label('Loại thông báo')
                    ->options([
                        'general' => 'Chung',
                        'schedule' => 'Lịch học',
                        'finance' => 'Học phí',
                        'progress' => 'Tiến bộ',
                        'content' => 'Nội dung học',
                    ])
                    ->default('general'),
                Select::make('channel')
                    ->label('Kênh')
                    ->options([
                        'portal' => 'Portal',
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'zalo' => 'Zalo',
                    ])
                    ->default('portal'),
                TextInput::make('title')
                    ->label('Tiêu đề')
                    ->required(),
                Textarea::make('body')
                    ->label('Nội dung')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã công bố',
                        'archived' => 'Lưu trữ',
                    ])
                    ->default('published'),
                Select::make('priority')
                    ->label('Ưu tiên')
                    ->options([
                        'low' => 'Thấp',
                        'normal' => 'Thường',
                        'high' => 'Cao',
                        'urgent' => 'Khẩn',
                    ])
                    ->default('normal'),
                DateTimePicker::make('published_at')
                    ->label('Ngày công bố'),
                DateTimePicker::make('read_at')
                    ->label('Ngày đọc'),
            ]);
    }
}
