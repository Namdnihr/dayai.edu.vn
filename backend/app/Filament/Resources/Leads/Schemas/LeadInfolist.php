<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('full_name')->label('Khách hàng'),
                TextEntry::make('phone')->label('SĐT')->placeholder('-'),
                TextEntry::make('email')->label('Email')->placeholder('-'),
                TextEntry::make('company_name')->label('Công ty')->placeholder('-'),
                TextEntry::make('lead_type')->label('Loại khách')->badge(),
                TextEntry::make('status')->label('Trạng thái')->badge(),
                TextEntry::make('priority')->label('Ưu tiên')->badge(),
                TextEntry::make('source.name')->label('Nguồn')->placeholder('-'),
                TextEntry::make('assignedUser.name')->label('Tư vấn viên')->placeholder('-'),
                TextEntry::make('learning_goal')->label('Mục tiêu học')->placeholder('-')->columnSpanFull(),
                TextEntry::make('message')->label('Nhu cầu')->placeholder('-')->columnSpanFull(),
                TextEntry::make('last_contacted_at')->label('Lần liên hệ gần nhất')->dateTime()->placeholder('-'),
                TextEntry::make('next_follow_up_at')->label('Follow-up tiếp theo')->dateTime()->placeholder('-'),
                TextEntry::make('created_at')->label('Ngày tạo')->dateTime(),
            ]);
    }
}
