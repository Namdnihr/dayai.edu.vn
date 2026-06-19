<?php

namespace App\Filament\Resources\StudentProfiles\Schemas;

use App\Models\StudentProfile;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('branch.name')
                    ->label('Branch')
                    ->placeholder('-'),
                TextEntry::make('person.id')
                    ->label('Person'),
                TextEntry::make('student_code'),
                TextEntry::make('student_type'),
                TextEntry::make('current_school')
                    ->placeholder('-'),
                TextEntry::make('current_company')
                    ->placeholder('-'),
                TextEntry::make('job_title')
                    ->placeholder('-'),
                TextEntry::make('organization.name')
                    ->label('Organization')
                    ->placeholder('-'),
                TextEntry::make('learning_goal')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('entry_level')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (StudentProfile $record): bool => $record->trashed()),
            ]);
    }
}
