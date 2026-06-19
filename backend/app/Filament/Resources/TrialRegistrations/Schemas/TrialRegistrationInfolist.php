<?php

namespace App\Filament\Resources\TrialRegistrations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TrialRegistrationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('lead.full_name')->label('Lead')->placeholder('-'),
                TextEntry::make('person.full_name')->label('Người đăng ký')->placeholder('-'),
                TextEntry::make('course_id')->label('Khóa quan tâm')->placeholder('-'),
                TextEntry::make('preferred_date')->label('Ngày mong muốn')->date()->placeholder('-'),
                TextEntry::make('preferred_time')->label('Khung giờ')->placeholder('-'),
                TextEntry::make('status')->label('Trạng thái')->badge(),
                TextEntry::make('note')->label('Ghi chú')->placeholder('-')->columnSpanFull(),
                TextEntry::make('createdBy.name')->label('Người tạo')->placeholder('-'),
            ]);
    }
}
