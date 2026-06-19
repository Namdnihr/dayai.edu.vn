<?php

namespace App\Filament\Resources\ProgressReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProgressReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('studentProfile.student_code')
                    ->label('Học viên')
                    ->searchable(),
                TextColumn::make('enrollment.enrollment_code')
                    ->label('Ghi danh')
                    ->searchable(),
                TextColumn::make('course.name')
                    ->label('Khóa học')
                    ->searchable(),
                TextColumn::make('classGroup.name')
                    ->label('Lớp')
                    ->searchable(),
                TextColumn::make('teacherProfile.teacher_code')
                    ->label('Giáo viên')
                    ->searchable(),
                TextColumn::make('report_period')
                    ->label('Kỳ')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Báo cáo')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->searchable(),
                TextColumn::make('overall_level')
                    ->label('Mức')
                    ->searchable(),
                TextColumn::make('progress_percent')
                    ->label('% tiến độ')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Ngày công bố')
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
