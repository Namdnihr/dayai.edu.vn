<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prompt')->label('C?u h?i')->limit(80)->searchable(),
                TextColumn::make('questionBank.name')->label('Ng?n h?ng')->searchable(),
                TextColumn::make('course.name')->label('Kh?a')->searchable()->toggleable(),
                TextColumn::make('courseModule.title')->label('Module')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('videoLesson.title')->label('Video')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('question_type')->label('Lo?i')->badge(),
                TextColumn::make('difficulty')->label('?? kh?')->badge(),
                TextColumn::make('default_score')->label('?i?m')->numeric()->sortable(),
                TextColumn::make('options_count')->label('??p ?n')->counts('options'),
                TextColumn::make('status')->label('Tr?ng th?i')->badge(),
            ])
            ->filters([
                SelectFilter::make('question_type')->label('Lo?i')->options([
                    'single_choice' => 'M?t ??p ?n',
                    'multiple_choice' => 'Nhi?u ??p ?n',
                    'true_false' => '??ng / Sai',
                    'short_answer' => 'Tr? l?i ng?n',
                    'essay' => 'T? lu?n',
                    'project' => 'D? ?n',
                ]),
                SelectFilter::make('difficulty')->label('?? kh?')->options([
                    'easy' => 'D?',
                    'medium' => 'Trung b?nh',
                    'hard' => 'Kh?',
                    'challenge' => 'Th? th?ch',
                ]),
                SelectFilter::make('status')->label('Tr?ng th?i')->options([
                    'draft' => 'Nh?p',
                    'published' => '?? duy?t',
                    'archived' => 'L?u tr?',
                ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
