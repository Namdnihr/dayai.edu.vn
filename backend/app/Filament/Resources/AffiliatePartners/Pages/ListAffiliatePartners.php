<?php

namespace App\Filament\Resources\AffiliatePartners\Pages;

use App\Filament\Resources\AffiliatePartners\AffiliatePartnerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAffiliatePartners extends ListRecords
{
    protected static string $resource = AffiliatePartnerResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
