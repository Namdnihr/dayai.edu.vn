<?php

namespace App\Filament\Resources\Questions;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\Questions\Pages\CreateQuestion;
use App\Filament\Resources\Questions\Pages\EditQuestion;
use App\Filament\Resources\Questions\Pages\ListQuestions;
use App\Models\Question;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class QuestionResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = Question::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::QuestionMarkCircle;

    protected static ?string $navigationLabel = 'Câu hỏi';

    protected static ?string $modelLabel = 'c?u h?i';

    protected static ?string $pluralModelLabel = 'c?u h?i';

    protected static string|UnitEnum|null $navigationGroup = 'Kiểm tra & Đánh giá';

    protected static ?int $navigationSort = 51;

    public static function form(Schema $schema): Schema
    {
        return Schemas\QuestionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return Tables\QuestionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestions::route('/'),
            'create' => CreateQuestion::route('/create'),
            'edit' => EditQuestion::route('/{record}/edit'),
        ];
    }
}
