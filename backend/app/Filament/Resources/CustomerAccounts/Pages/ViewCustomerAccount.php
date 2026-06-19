<?php

namespace App\Filament\Resources\CustomerAccounts\Pages;

use App\Filament\Resources\CustomerAccounts\CustomerAccountResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomerAccount extends ViewRecord
{
    protected static string $resource = CustomerAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
