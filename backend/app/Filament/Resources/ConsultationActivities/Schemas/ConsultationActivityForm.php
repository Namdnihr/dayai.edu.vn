<?php

namespace App\Filament\Resources\ConsultationActivities\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ConsultationActivityForm
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
                    ->label('Cá nhân liên quan')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                Select::make('organization_id')
                    ->label('Doanh nghiệp liên quan')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('activity_type')
                    ->label('Loại hoạt động')
                    ->options([
                        'call' => 'Gọi điện',
                        'zalo' => 'Zalo',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'note' => 'Ghi chú',
                        'trial' => 'Học thử',
                        'other' => 'Khác',
                    ])
                    ->default('call')
                    ->required(),
                Select::make('direction')
                    ->label('Chiều tương tác')
                    ->options([
                        'inbound' => 'Khách liên hệ vào',
                        'outbound' => 'Trung tâm liên hệ ra',
                        'internal' => 'Ghi chú nội bộ',
                    ]),
                TextInput::make('subject')
                    ->label('Tiêu đề')
                    ->maxLength(255),
                Textarea::make('content')
                    ->label('Nội dung tư vấn')
                    ->required()
                    ->columnSpanFull(),
                Select::make('outcome')
                    ->label('Kết quả')
                    ->options([
                        'interested' => 'Quan tâm',
                        'need_follow_up' => 'Cần follow-up',
                        'trial_booked' => 'Đã hẹn học thử',
                        'registered' => 'Đã đăng ký',
                        'not_interested' => 'Không quan tâm',
                        'no_answer' => 'Không nghe máy',
                    ]),
                DateTimePicker::make('activity_at')
                    ->label('Thời điểm tư vấn')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('next_follow_up_at')
                    ->label('Lịch follow-up tiếp theo'),
                Select::make('created_by_id')
                    ->label('Người ghi nhận')
                    ->relationship('createdBy', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
