<?php

namespace App\Filament\Resources\Certificates\Schemas;

use App\Models\Certificate;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificateInfolist
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
                TextEntry::make('certificate_code'),
                TextEntry::make('title'),
                TextEntry::make('status'),
                TextEntry::make('final_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('grade')
                    ->placeholder('-'),
                TextEntry::make('issued_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expires_at')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('file_path')
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
                    ->visible(fn (Certificate $record): bool => $record->trashed()),
            ]);
    }
}
