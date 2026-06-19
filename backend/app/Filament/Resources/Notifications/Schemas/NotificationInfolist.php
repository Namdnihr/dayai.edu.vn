<?php

namespace App\Filament\Resources\Notifications\Schemas;

use App\Models\Notification;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NotificationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('person.id')
                    ->label('Person')
                    ->placeholder('-'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile')
                    ->placeholder('-'),
                TextEntry::make('organization.name')
                    ->label('Organization')
                    ->placeholder('-'),
                TextEntry::make('audience_type'),
                TextEntry::make('notification_type'),
                TextEntry::make('channel'),
                TextEntry::make('title'),
                TextEntry::make('body')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('priority'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('read_at')
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
                    ->visible(fn (Notification $record): bool => $record->trashed()),
            ]);
    }
}
