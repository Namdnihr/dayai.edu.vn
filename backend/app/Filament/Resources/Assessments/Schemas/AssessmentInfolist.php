<?php

namespace App\Filament\Resources\Assessments\Schemas;

use App\Models\Assessment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssessmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('course.name')
                    ->label('Course')
                    ->placeholder('-'),
                TextEntry::make('classGroup.name')
                    ->label('Class group')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('assessment_type'),
                TextEntry::make('status'),
                TextEntry::make('max_score')
                    ->numeric(),
                TextEntry::make('weight_percent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('assessment_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('description')
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
                    ->visible(fn (Assessment $record): bool => $record->trashed()),
            ]);
    }
}
