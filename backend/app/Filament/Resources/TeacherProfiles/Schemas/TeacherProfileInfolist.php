<?php

namespace App\Filament\Resources\TeacherProfiles\Schemas;

use App\Models\TeacherProfile;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeacherProfileInfolist
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
                TextEntry::make('teacher_code'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TeacherProfile $record): bool => $record->trashed()),
            ]);
    }
}
