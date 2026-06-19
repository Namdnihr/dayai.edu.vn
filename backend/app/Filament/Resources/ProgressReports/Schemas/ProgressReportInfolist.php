<?php

namespace App\Filament\Resources\ProgressReports\Schemas;

use App\Models\ProgressReport;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgressReportInfolist
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
                TextEntry::make('course.name')
                    ->label('Course')
                    ->placeholder('-'),
                TextEntry::make('classGroup.name')
                    ->label('Class group')
                    ->placeholder('-'),
                TextEntry::make('teacherProfile.title')
                    ->label('Teacher profile')
                    ->placeholder('-'),
                TextEntry::make('report_period'),
                TextEntry::make('title'),
                TextEntry::make('status'),
                TextEntry::make('overall_level')
                    ->placeholder('-'),
                TextEntry::make('progress_percent')
                    ->numeric(),
                TextEntry::make('strengths')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('improvements')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('recommendation')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('published_at')
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
                    ->visible(fn (ProgressReport $record): bool => $record->trashed()),
            ]);
    }
}
