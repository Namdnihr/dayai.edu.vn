<?php

namespace App\Filament\Resources\CourseModules\Schemas;

use App\Models\CourseModule;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseModuleInfolist
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
                    ->label('Course'),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('duration_minutes')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CourseModule $record): bool => $record->trashed()),
            ]);
    }
}
