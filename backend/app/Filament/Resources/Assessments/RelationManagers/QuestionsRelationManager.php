<?php

namespace App\Filament\Resources\Assessments\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessmentQuestions';

    protected static ?string $title = 'C?u h?i trong b?i';

    protected static ?string $modelLabel = 'c?u h?i trong b?i';

    protected static ?string $pluralModelLabel = 'c?u h?i trong b?i';

    public function form(Schema $schema): Schema
    {
        $assessment = $this->getOwnerRecord();

        return $schema->components([
            Hidden::make('tenant_id')->default($assessment->tenant_id),
            Select::make('question_id')
                ->label('C?u h?i')
                ->relationship('question', 'prompt')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('sort_order')
                ->label('Th? t?')
                ->numeric()
                ->default(fn () => (int) $assessment->assessmentQuestions()->max('sort_order') + 1)
                ->required(),
            TextInput::make('score')
                ->label('?i?m')
                ->numeric()
                ->default(1)
                ->required(),
            Toggle::make('is_required')
                ->label('B?t bu?c')
                ->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('Th? t?')->sortable(),
                TextColumn::make('question.prompt')->label('C?u h?i')->limit(90)->searchable(),
                TextColumn::make('question.question_type')->label('Lo?i')->badge(),
                TextColumn::make('question.difficulty')->label('?? kh?')->badge(),
                TextColumn::make('score')->label('?i?m')->numeric()->sortable(),
                IconColumn::make('is_required')->label('B?t bu?c')->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Th?m c?u h?i')
                    ->mutateDataUsing(function (array $data): array {
                        $data['tenant_id'] = $this->getOwnerRecord()->tenant_id;

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
