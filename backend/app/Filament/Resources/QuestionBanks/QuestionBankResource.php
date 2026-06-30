<?php

namespace App\Filament\Resources\QuestionBanks;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\QuestionBanks\Pages\CreateQuestionBank;
use App\Filament\Resources\QuestionBanks\Pages\EditQuestionBank;
use App\Filament\Resources\QuestionBanks\Pages\ListQuestionBanks;
use App\Models\QuestionBank;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QuestionBankResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = QuestionBank::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;

    protected static ?string $navigationLabel = 'Ng?n h?ng c?u h?i';

    protected static ?string $modelLabel = 'ng?n h?ng c?u h?i';

    protected static ?string $pluralModelLabel = 'ng?n h?ng c?u h?i';

    protected static string|UnitEnum|null $navigationGroup = 'LMS & Kh?a h?c';

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return Schemas\QuestionBankForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\QuestionBanksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionBanks::route('/'),
            'create' => CreateQuestionBank::route('/create'),
            'edit' => EditQuestionBank::route('/{record}/edit'),
        ];
    }
}
