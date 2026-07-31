<?php

namespace App\Filament\Resources\AffiliateClicks;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AffiliateClicks\Pages\ListAffiliateClicks;
use App\Models\AffiliateClick;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AffiliateClickResource extends Resource
{
    use AuthorizesResourceAccess;
    protected static ?string $model = AffiliateClick::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::CursorArrowRays;
    protected static ?string $navigationLabel = 'Click affiliate';
    protected static ?string $modelLabel = 'click affiliate';
    protected static ?string $pluralModelLabel = 'click affiliate';
    protected static string|UnitEnum|null $navigationGroup = 'Affiliate';
    protected static ?int $navigationSort = 33;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('partner.name')->label('Đối tác')->searchable(),
            TextColumn::make('link.code')->label('Link')->searchable(),
            TextColumn::make('lead.full_name')->label('Lead')->searchable(),
            TextColumn::make('affiliate_code')->label('Aff code')->searchable(),
            TextColumn::make('referral_code')->label('Ref code')->searchable(),
            TextColumn::make('click_id')->label('Click ID')->searchable()->toggleable(),
            TextColumn::make('landing_page')->label('Landing')->limit(40)->toggleable(),
            TextColumn::make('clicked_at')->label('Thời điểm')->dateTime()->sortable(),
        ])->defaultSort('clicked_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ListAffiliateClicks::route('/')];
    }
}
