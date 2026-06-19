<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Payment;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
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
                TextEntry::make('order.id')
                    ->label('Order'),
                TextEntry::make('customerAccount.id')
                    ->label('Customer account'),
                TextEntry::make('payment_code'),
                TextEntry::make('payment_method'),
                TextEntry::make('status'),
                TextEntry::make('amount_vnd')
                    ->numeric(),
                TextEntry::make('paid_at')
                    ->dateTime(),
                TextEntry::make('reference_no')
                    ->placeholder('-'),
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
                    ->visible(fn (Payment $record): bool => $record->trashed()),
            ]);
    }
}
