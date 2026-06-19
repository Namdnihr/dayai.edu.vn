<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ActivityLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Đơn vị')
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('Người dùng')
                    ->placeholder('-'),
                TextEntry::make('action')
                    ->label('Hành động'),
                TextEntry::make('subject_type')
                    ->label('Loại đối tượng')
                    ->placeholder('-'),
                TextEntry::make('subject_id')
                    ->label('ID đối tượng')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('Mô tả')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ip_address')
                    ->label('IP')
                    ->placeholder('-'),
                TextEntry::make('user_agent')
                    ->label('User agent')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->label('Thời điểm')
                    ->dateTime(),
            ]);
    }
}
