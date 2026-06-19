<?php

namespace App\Filament\Resources\TeacherComments\Schemas;

use App\Models\TeacherComment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TeacherCommentInfolist
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
                TextEntry::make('enrollment.id')
                    ->label('Enrollment')
                    ->placeholder('-'),
                TextEntry::make('classSession.title')
                    ->label('Class session')
                    ->placeholder('-'),
                TextEntry::make('teacherProfile.title')
                    ->label('Teacher profile')
                    ->placeholder('-'),
                TextEntry::make('comment_type'),
                TextEntry::make('visibility'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('comment')
                    ->columnSpanFull(),
                TextEntry::make('rating')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('commented_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TeacherComment $record): bool => $record->trashed()),
            ]);
    }
}
