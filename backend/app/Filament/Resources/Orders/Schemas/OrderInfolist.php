<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Order;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('branch.name')
                    ->label('Branch')
                    ->placeholder('-'),
                TextEntry::make('customerAccount.id')
                    ->label('Customer account'),
                TextEntry::make('lead.id')
                    ->label('Lead')
                    ->placeholder('-'),
                TextEntry::make('order_code'),
                TextEntry::make('order_type'),
                TextEntry::make('status'),
                TextEntry::make('ordered_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('subtotal_vnd')
                    ->numeric(),
                TextEntry::make('discount_vnd')
                    ->numeric(),
                TextEntry::make('total_vnd')
                    ->numeric(),
                TextEntry::make('paid_vnd')
                    ->numeric(),
                TextEntry::make('balance_vnd')
                    ->numeric(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('createdBy.name')
                    ->label('Created by')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Order $record): bool => $record->trashed()),
            ]);
    }
}
