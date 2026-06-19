<?php

namespace App\Filament\Resources\CustomerAccounts\Schemas;

use App\Models\CustomerAccount;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomerAccountInfolist
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
                TextEntry::make('customer_code'),
                TextEntry::make('account_type'),
                TextEntry::make('person.id')
                    ->label('Person')
                    ->placeholder('-'),
                TextEntry::make('organization.name')
                    ->label('Organization')
                    ->placeholder('-'),
                TextEntry::make('display_name'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('billing_address')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('tax_code')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (CustomerAccount $record): bool => $record->trashed()),
            ]);
    }
}
