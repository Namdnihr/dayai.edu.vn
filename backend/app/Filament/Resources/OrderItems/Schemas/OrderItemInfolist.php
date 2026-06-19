<?php

namespace App\Filament\Resources\OrderItems\Schemas;

use App\Models\OrderItem;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class OrderItemInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('course.name')
                    ->label('Course'),
                TextEntry::make('classGroup.name')
                    ->label('Class group')
                    ->placeholder('-'),
                TextEntry::make('studentProfile.id')
                    ->label('Student profile')
                    ->placeholder('-'),
                TextEntry::make('description'),
                TextEntry::make('quantity')
                    ->numeric(),
                TextEntry::make('unit_price_vnd')
                    ->numeric(),
                TextEntry::make('discount_vnd')
                    ->numeric(),
                TextEntry::make('line_total_vnd')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (OrderItem $record): bool => $record->trashed()),
            ]);
    }
}
