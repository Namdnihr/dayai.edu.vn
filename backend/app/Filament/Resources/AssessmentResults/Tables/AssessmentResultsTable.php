<?php

namespace App\Filament\Resources\AssessmentResults\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class AssessmentResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('assessment.title')
                    ->label('Bài đánh giá')
                    ->searchable(),
                TextColumn::make('studentProfile.student_code')
                    ->label('Học viên')
                    ->searchable(),
                TextColumn::make('enrollment.enrollment_code')
                    ->label('Ghi danh')
                    ->searchable(),
                TextColumn::make('teacherProfile.teacher_code')
                    ->label('Giáo viên')
                    ->searchable(),
                TextColumn::make('score')
                    ->label('Điểm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('max_score')
                    ->label('Tối đa')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('level')
                    ->label('Mức')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->searchable(),
                TextColumn::make('assessed_at')
                    ->label('Ngày chấm')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
