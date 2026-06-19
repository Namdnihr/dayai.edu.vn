<?php

namespace App\Filament\Resources\TrialRegistrations\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TrialRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->default(fn () => Tenant::query()->value('id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('lead_id')
                    ->label('Lead')
                    ->relationship('lead', 'full_name')
                    ->searchable()
                    ->preload(),
                Select::make('person_id')
                    ->label('Người đăng ký')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                TextInput::make('course_id')
                    ->label('Khóa quan tâm')
                    ->helperText('Sẽ đổi sang select khóa học khi Sprint Learning tạo bảng courses.')
                    ->maxLength(255),
                DatePicker::make('preferred_date')
                    ->label('Ngày mong muốn'),
                TextInput::make('preferred_time')
                    ->label('Khung giờ mong muốn')
                    ->maxLength(100),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'requested' => 'Mới yêu cầu',
                        'scheduled' => 'Đã xếp lịch',
                        'attended' => 'Đã tham gia',
                        'no_show' => 'Không tham gia',
                        'cancelled' => 'Đã hủy',
                        'converted' => 'Đã chuyển đổi',
                    ])
                    ->default('requested')
                    ->required(),
                Textarea::make('note')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
                Select::make('created_by_id')
                    ->label('Người tạo')
                    ->relationship('createdBy', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }
}
