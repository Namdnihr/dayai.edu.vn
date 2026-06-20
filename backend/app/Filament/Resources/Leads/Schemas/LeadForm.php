<?php

namespace App\Filament\Resources\Leads\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
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
                Select::make('lead_source_id')
                    ->label('Nguồn lead')
                    ->relationship('source', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('assigned_user_id')
                    ->label('Tư vấn viên phụ trách')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('lead_type')
                    ->label('Loại khách')
                    ->options([
                        'parent' => 'Phụ huynh mua cho con',
                        'student' => 'Sinh viên / cá nhân tự đăng ký',
                        'business_owner' => 'Chủ doanh nghiệp đi học',
                        'company' => 'Công ty mua cho nhân sự',
                        'unknown' => 'Chưa rõ',
                    ])
                    ->default('unknown')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'new' => 'Mới',
                        'trial_requested' => 'Yêu cầu học thử',
                        'contacting' => 'Đang liên hệ',
                        'consulting' => 'Đang tư vấn',
                        'trial_scheduled' => 'Đã hẹn học thử',
                        'registered' => 'Đã đăng ký',
                        'not_fit' => 'Không phù hợp',
                        'lost' => 'Mất liên hệ',
                        'duplicate' => 'Trùng',
                    ])
                    ->default('new')
                    ->required(),
                Select::make('priority')
                    ->label('Ưu tiên')
                    ->options([
                        'low' => 'Thấp',
                        'normal' => 'Bình thường',
                        'high' => 'Cao',
                        'urgent' => 'Khẩn cấp',
                    ])
                    ->default('normal')
                    ->required(),
                Select::make('temperature')
                    ->label('Độ nóng lead')
                    ->options([
                        'cold' => 'Lạnh',
                        'warm' => 'Ấm',
                        'hot' => 'Nóng',
                    ])
                    ->default('warm')
                    ->required(),
                Select::make('pipeline_stage')
                    ->label('Giai đoạn pipeline')
                    ->options([
                        'new' => 'Lead mới',
                        'contacting' => 'Đang liên hệ',
                        'consulting' => 'Đang tư vấn',
                        'trial' => 'Học thử',
                        'won' => 'Chốt thành công',
                        'lost' => 'Mất lead',
                        'closed' => 'Đóng',
                    ])
                    ->default('new')
                    ->required(),
                TextInput::make('full_name')
                    ->label('Họ tên người liên hệ')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('phone')
                    ->label('SĐT')
                    ->maxLength(30)
                    ->tel()
                    ->rules(['required_without:email']),
                TextInput::make('email')
                    ->label('Email')
                    ->maxLength(255)
                    ->email()
                    ->rules(['required_without:phone']),
                TextInput::make('company_name')
                    ->label('Tên công ty')
                    ->maxLength(255),
                TextInput::make('interested_course_id')
                    ->label('Mã khóa quan tâm')
                    ->maxLength(255),
                TextInput::make('expected_value_vnd')
                    ->label('Giá trị cơ hội dự kiến VND')
                    ->numeric()
                    ->default(0),
                TextInput::make('course_slug')
                    ->label('Slug khóa/landing')
                    ->maxLength(255),
                Select::make('person_id')
                    ->label('Liên kết cá nhân có sẵn')
                    ->relationship('person', 'full_name')
                    ->searchable()
                    ->preload(),
                Select::make('organization_id')
                    ->label('Liên kết doanh nghiệp có sẵn')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
                Textarea::make('learning_goal')
                    ->label('Mục tiêu học')
                    ->columnSpanFull(),
                Textarea::make('message')
                    ->label('Nhu cầu / nội dung form')
                    ->columnSpanFull(),
                Select::make('preferred_contact_method')
                    ->label('Kênh liên hệ ưu tiên')
                    ->options([
                        'phone' => 'Điện thoại',
                        'zalo' => 'Zalo',
                        'email' => 'Email',
                        'messenger' => 'Messenger',
                        'other' => 'Khác',
                    ]),
                TextInput::make('utm_source')->label('UTM source')->maxLength(255),
                TextInput::make('utm_medium')->label('UTM medium')->maxLength(255),
                TextInput::make('utm_campaign')->label('UTM campaign')->maxLength(255),
                TextInput::make('utm_content')->label('UTM content')->maxLength(255),
                TextInput::make('utm_term')->label('UTM term')->maxLength(255),
                TextInput::make('affiliate_code')->label('Affiliate code')->maxLength(255),
                TextInput::make('referral_code')->label('Referral code')->maxLength(255),
                TextInput::make('click_id')->label('Click ID')->maxLength(255),
                TextInput::make('first_touch_source')->label('First-touch source')->maxLength(255),
                TextInput::make('last_touch_source')->label('Last-touch source')->maxLength(255),
                TextInput::make('page_url')
                    ->label('Page URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('landing_page')
                    ->label('Landing page')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                TextInput::make('referrer_url')
                    ->label('Referrer URL')
                    ->maxLength(1000)
                    ->columnSpanFull(),
                DateTimePicker::make('last_contacted_at')
                    ->label('Lần liên hệ gần nhất'),
                DateTimePicker::make('next_follow_up_at')
                    ->label('Lịch follow-up tiếp theo'),
                DateTimePicker::make('converted_at')
                    ->label('Thời điểm chuyển đổi'),
                Textarea::make('lost_reason')
                    ->label('Lý do mất/không phù hợp')
                    ->columnSpanFull(),
                Select::make('lost_reason_type')
                    ->label('Nhóm lý do mất lead')
                    ->options([
                        'price' => 'Chưa phù hợp học phí',
                        'schedule' => 'Không phù hợp lịch học',
                        'no_response' => 'Không phản hồi',
                        'not_ready' => 'Chưa sẵn sàng học',
                        'competitor' => 'Chọn đơn vị khác',
                        'not_fit' => 'Không đúng đối tượng',
                        'other' => 'Khác',
                    ]),
            ]);
    }
}
