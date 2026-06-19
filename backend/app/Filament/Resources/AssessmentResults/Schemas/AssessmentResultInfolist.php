<?php

namespace App\Filament\Resources\AssessmentResults\Schemas;

use App\Models\AssessmentResult;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssessmentResultInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('assessment.title')
                    ->label('Assessment'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile'),
                TextEntry::make('enrollment.id')
                    ->label('Enrollment')
                    ->placeholder('-'),
                TextEntry::make('teacherProfile.title')
                    ->label('Teacher profile')
                    ->placeholder('-'),
                TextEntry::make('score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('max_score')
                    ->numeric(),
                TextEntry::make('level')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('feedback')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('strengths')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('improvements')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('assessed_at')
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
                    ->visible(fn (AssessmentResult $record): bool => $record->trashed()),
            ]);
    }
}
