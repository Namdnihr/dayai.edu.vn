<?php

namespace App\Filament\Resources\GuardianRelations\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GuardianRelationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('guardianPerson.id')
                    ->label('Guardian person'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile'),
                TextEntry::make('relation_type'),
                IconEntry::make('is_primary')
                    ->boolean(),
                IconEntry::make('can_view_finance')
                    ->boolean(),
                IconEntry::make('can_view_progress')
                    ->boolean(),
                IconEntry::make('can_receive_notifications')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
