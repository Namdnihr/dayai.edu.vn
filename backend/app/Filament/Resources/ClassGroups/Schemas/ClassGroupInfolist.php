<?php

namespace App\Filament\Resources\ClassGroups\Schemas;

use App\Models\ClassGroup;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ClassGroupInfolist
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
                TextEntry::make('course.name')
                    ->label('Course'),
                TextEntry::make('teacherProfile.title')
                    ->label('Teacher profile')
                    ->placeholder('-'),
                TextEntry::make('class_code'),
                TextEntry::make('name'),
                TextEntry::make('learning_format'),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('max_students')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('schedule_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('location')
                    ->placeholder('-'),
                TextEntry::make('organization.name')
                    ->label('Organization')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (ClassGroup $record): bool => $record->trashed()),
            ]);
    }
}
