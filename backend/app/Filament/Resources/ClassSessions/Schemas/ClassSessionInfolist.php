<?php

namespace App\Filament\Resources\ClassSessions\Schemas;

use App\Models\ClassSession;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClassSessionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('classGroup.name')
                    ->label('Class group'),
                TextEntry::make('courseModule.title')
                    ->label('Course module')
                    ->placeholder('-'),
                TextEntry::make('teacherProfile.title')
                    ->label('Teacher profile')
                    ->placeholder('-'),
                TextEntry::make('session_no')
                    ->numeric(),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('ends_at')
                    ->dateTime(),
                TextEntry::make('status'),
                TextEntry::make('location')
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
                    ->visible(fn (ClassSession $record): bool => $record->trashed()),
            ]);
    }
}
