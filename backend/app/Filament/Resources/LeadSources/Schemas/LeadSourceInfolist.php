<?php

namespace App\Filament\Resources\LeadSources\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LeadSourceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('Nguồn'),
                TextEntry::make('code')->label('Mã'),
                TextEntry::make('source_type')->label('Loại')->badge(),
                IconEntry::make('is_active')->label('Đang dùng')->boolean(),
                TextEntry::make('description')->label('Mô tả')->placeholder('-')->columnSpanFull(),
                TextEntry::make('created_at')->label('Ngày tạo')->dateTime(),
            ]);
    }
}
