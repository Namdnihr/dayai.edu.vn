<?php

namespace App\Filament\Resources\LeadAssignments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadAssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('lead.full_name')->label('Lead'),
                TextEntry::make('assignedToUser.name')->label('Tư vấn viên'),
                TextEntry::make('assignedByUser.name')->label('Người phân công')->placeholder('-'),
                TextEntry::make('assigned_at')->label('Thời điểm phân công')->dateTime(),
                TextEntry::make('unassigned_at')->label('Thời điểm kết thúc')->dateTime()->placeholder('-'),
                TextEntry::make('note')->label('Ghi chú')->placeholder('-')->columnSpanFull(),
            ]);
    }
}
