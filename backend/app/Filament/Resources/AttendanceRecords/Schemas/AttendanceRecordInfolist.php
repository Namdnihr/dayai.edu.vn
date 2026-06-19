<?php

namespace App\Filament\Resources\AttendanceRecords\Schemas;

use App\Models\AttendanceRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendanceRecordInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('classSession.title')
                    ->label('Class session'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile'),
                TextEntry::make('enrollment.id')
                    ->label('Enrollment')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('checked_in_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('minutes_late')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('absence_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('teacher_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('checkedBy.name')
                    ->label('Checked by')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (AttendanceRecord $record): bool => $record->trashed()),
            ]);
    }
}
