<?php

namespace App\Filament\Resources\ConsultationActivities\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ConsultationActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('lead.full_name')->label('Lead')->placeholder('-'),
                TextEntry::make('activity_type')->label('Loại hoạt động')->badge(),
                TextEntry::make('direction')->label('Chiều tương tác')->placeholder('-'),
                TextEntry::make('subject')->label('Tiêu đề')->placeholder('-'),
                TextEntry::make('content')->label('Nội dung')->columnSpanFull(),
                TextEntry::make('outcome')->label('Kết quả')->badge()->placeholder('-'),
                TextEntry::make('activity_at')->label('Thời điểm')->dateTime(),
                TextEntry::make('next_follow_up_at')->label('Follow-up')->dateTime()->placeholder('-'),
                TextEntry::make('createdBy.name')->label('Người ghi'),
            ]);
    }
}
