<?php

namespace App\Filament\Resources\Enrollments\Schemas;

use App\Models\Enrollment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EnrollmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile'),
                TextEntry::make('course.name')
                    ->label('Course'),
                TextEntry::make('classGroup.name')
                    ->label('Class group')
                    ->placeholder('-'),
                TextEntry::make('order_id')
                    ->placeholder('-'),
                TextEntry::make('enrollment_code'),
                TextEntry::make('status'),
                TextEntry::make('enrolled_at')
                    ->dateTime(),
                TextEntry::make('started_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Enrollment $record): bool => $record->trashed()),
            ]);
    }
}
