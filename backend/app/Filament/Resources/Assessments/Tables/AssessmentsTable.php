<?php

namespace App\Filament\Resources\Assessments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AssessmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('B?i ??nh gi?')
                    ->searchable(),
                TextColumn::make('course.name')
                    ->label('Kh?a h?c')
                    ->searchable(),
                TextColumn::make('courseModule.title')
                    ->label('Module')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('videoLesson.title')
                    ->label('Video')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('classGroup.name')
                    ->label('L?p')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('assessment_type')
                    ->label('Lo?i')
                    ->badge(),
                TextColumn::make('assessment_questions_count')
                    ->label('S? c?u')
                    ->counts('assessmentQuestions'),
                TextColumn::make('status')
                    ->label('Tr?ng th?i')
                    ->badge(),
                TextColumn::make('max_score')
                    ->label('?i?m t?i ?a')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('assessment_at')
                    ->label('Ng?y ??nh gi?')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('assessment_type')
                    ->label('Lo?i')
                    ->options([
                        'entry' => '??u v?o',
                        'quiz' => 'Quiz nhanh',
                        'practice' => 'B?i luy?n t?p',
                        'project' => 'D? ?n',
                        'final' => 'Cu?i kh?a',
                        'progress' => 'Ti?n b?',
                    ]),
                SelectFilter::make('status')
                    ->label('Tr?ng th?i')
                    ->options([
                        'draft' => 'Nh?p',
                        'published' => '?? c?ng b?',
                        'archived' => 'L?u tr?',
                    ]),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
