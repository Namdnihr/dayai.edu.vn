<?php

namespace App\Filament\Resources\Receipts\Schemas;

use App\Models\Receipt;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReceiptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('payment.id')
                    ->label('Payment'),
                TextEntry::make('receipt_code'),
                TextEntry::make('issued_at')
                    ->dateTime(),
                TextEntry::make('issuedBy.name')
                    ->label('Issued by')
                    ->placeholder('-'),
                TextEntry::make('payer_name'),
                TextEntry::make('amount_vnd')
                    ->numeric(),
                TextEntry::make('content')
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
                    ->visible(fn (Receipt $record): bool => $record->trashed()),
            ]);
    }
}
