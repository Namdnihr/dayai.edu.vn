<?php

namespace App\Filament\Resources\QuizAttempts;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\QuizAttempts\Pages\EditQuizAttempt;
use App\Filament\Resources\QuizAttempts\Pages\ListQuizAttempts;
use App\Filament\Resources\QuizAttempts\Pages\ViewQuizAttempt;
use App\Filament\Resources\QuizAttempts\RelationManagers;
use App\Filament\Resources\QuizAttempts\Schemas\QuizAttemptForm;
use App\Filament\Resources\QuizAttempts\Schemas\QuizAttemptInfolist;
use App\Filament\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use App\Models\QuizAttempt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class QuizAttemptResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = QuizAttempt::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;
    protected static ?string $navigationLabel = "L\u{1B0}\u{1EE3}t l\u{E0}m b\u{E0}i";
    protected static ?string $modelLabel = "l\u{1B0}\u{1EE3}t l\u{E0}m b\u{E0}i";
    protected static ?string $pluralModelLabel = "l\u{1B0}\u{1EE3}t l\u{E0}m b\u{E0}i";
    protected static string|UnitEnum|null $navigationGroup = "LMS & Kh\u{F3}a h\u{1ECD}c";
    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema { return QuizAttemptForm::configure($schema); }
    public static function infolist(Schema $schema): Schema { return QuizAttemptInfolist::configure($schema); }
    public static function table(Table $table): Table { return QuizAttemptsTable::configure($table); }
    public static function getRelations(): array { return [RelationManagers\AnswersRelationManager::class]; }
    public static function getPages(): array { return ['index' => ListQuizAttempts::route('/'), 'view' => ViewQuizAttempt::route('/{record}'), 'edit' => EditQuizAttempt::route('/{record}/edit')]; }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
