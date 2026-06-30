<?php

namespace App\Filament\Resources\QuizAttempts\RelationManagers;

use App\Models\QuizAttemptAnswer;
use App\Services\QuizAttemptScoringService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';
    protected static ?string $title = "C\u{E2}u tr\u{1EA3} l\u{1EDD}i & ch\u{1EA5}m \u{111}i\u{1EC3}m";
    protected static ?string $modelLabel = "c\u{E2}u tr\u{1EA3} l\u{1EDD}i";
    protected static ?string $pluralModelLabel = "c\u{E2}u tr\u{1EA3} l\u{1EDD}i";

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Placeholder::make('question_prompt')->label("C\u{E2}u h\u{1ECF}i")->content(fn (?QuizAttemptAnswer $record): string => $record?->question?->prompt ?? '-'),
            Placeholder::make('question_type')->label("Lo\u{1EA1}i c\u{E2}u h\u{1ECF}i")->content(fn (?QuizAttemptAnswer $record): string => self::formatQuestionType($record?->question?->question_type)),
            Placeholder::make('selected_options')->label("\u{110}\u{E1}p \u{E1}n \u{111}\u{E3} ch\u{1ECD}n")->content(fn (?QuizAttemptAnswer $record): string => self::selectedOptionText($record)),
            Textarea::make('answer_text')->label("C\u{E2}u tr\u{1EA3} l\u{1EDD}i t\u{1EF1} lu\u{1EAD}n")->disabled()->columnSpanFull(),
            Select::make('is_correct')->label("K\u{1EBF}t qu\u{1EA3}")->options([1 => "\u{110}\u{FA}ng", 0 => "Sai / ch\u{1B0}a \u{111}\u{1EA1}t"])->native(false),
            TextInput::make('score_awarded')->label("\u{110}i\u{1EC3}m ch\u{1EA5}m")->numeric()->required(),
            Textarea::make('teacher_feedback')->label("Nh\u{1EAD}n x\u{E9}t c\u{1EE7}a gi\u{E1}o vi\u{EA}n")->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assessmentQuestion.sort_order')->label("C\u{E2}u")->sortable(),
                TextColumn::make('question.prompt')->label("N\u{1ED9}i dung c\u{E2}u h\u{1ECF}i")->limit(80)->searchable(),
                TextColumn::make('question.question_type')->label("Lo\u{1EA1}i")->badge()->formatStateUsing(fn (?string $state): string => self::formatQuestionType($state)),
                TextColumn::make('answer_text')->label("T\u{1EF1} lu\u{1EAD}n")->limit(60)->placeholder('-'),
                TextColumn::make('score_awarded')->label("\u{110}i\u{1EC3}m")->numeric(2)->sortable(),
                IconColumn::make('is_correct')->label("\u{110}\u{FA}ng")->boolean(),
                TextColumn::make('teacher_feedback')->label("Nh\u{1EAD}n x\u{E9}t")->limit(60)->placeholder('-'),
            ])
            ->headerActions([
                Action::make('recalculate')->label("C\u{1EAD}p nh\u{1EAD}t t\u{1ED5}ng \u{111}i\u{1EC3}m")->color('info')->action(function (): void {
                    app(QuizAttemptScoringService::class)->recalculate($this->getOwnerRecord());
                    Notification::make()->title("\u{110}\u{E3} c\u{1EAD}p nh\u{1EAD}t t\u{1ED5}ng \u{111}i\u{1EC3}m t\u{1EA1}m t\u{ED}nh")->success()->send();
                }),
                Action::make('grade')->label("Ch\u{1ED1}t \u{111}i\u{1EC3}m")->color('success')->requiresConfirmation()->action(function (): void {
                    app(QuizAttemptScoringService::class)->recalculate($this->getOwnerRecord(), markGraded: true);
                    Notification::make()->title("\u{110}\u{E3} ch\u{1ED1}t \u{111}i\u{1EC3}m v\u{E0} xu\u{1EA5}t b\u{1EA3}n k\u{1EBF}t qu\u{1EA3}")->success()->send();
                }),
            ])
            ->recordActions([
                EditAction::make()->label("Ch\u{1EA5}m c\u{E2}u")->after(function (QuizAttemptAnswer $record): void {
                    app(QuizAttemptScoringService::class)->recalculate($record->quizAttempt);
                }),
            ]);
    }

    protected static function selectedOptionText(?QuizAttemptAnswer $record): string
    {
        if (! $record || empty($record->selected_option_ids)) { return '-'; }
        return $record->question?->options->whereIn('id', $record->selected_option_ids)->pluck('content')->implode('; ') ?: '-';
    }

    protected static function formatQuestionType(?string $type): string
    {
        return match ($type) {
            'single_choice' => "M\u{1ED9}t \u{111}\u{E1}p \u{E1}n",
            'multiple_choice' => "Nhi\u{1EC1}u \u{111}\u{E1}p \u{E1}n",
            'true_false' => "\u{110}\u{FA}ng / Sai",
            'short_answer' => "Tr\u{1EA3} l\u{1EDD}i ng\u{1EAF}n",
            'essay' => "T\u{1EF1} lu\u{1EAD}n",
            'project' => "D\u{1EF1} \u{E1}n",
            default => $type ?? '-',
        };
    }
}
