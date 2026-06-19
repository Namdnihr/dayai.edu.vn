<?php

namespace App\Filament\Resources\Receivables\Schemas;

use App\Models\Receivable;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReceivableInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('invoice.id')
                    ->label('Invoice'),
                TextEntry::make('customerAccount.id')
                    ->label('Customer account'),
                TextEntry::make('status'),
                TextEntry::make('original_amount_vnd')
                    ->numeric(),
                TextEntry::make('paid_vnd')
                    ->numeric(),
                TextEntry::make('balance_vnd')
                    ->numeric(),
                TextEntry::make('due_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Receivable $record): bool => $record->trashed()),
            ]);
    }
}
