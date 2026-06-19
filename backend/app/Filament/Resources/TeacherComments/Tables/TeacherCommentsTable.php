<?php

namespace App\Filament\Resources\TeacherComments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class TeacherCommentsTable
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
                TextColumn::make('classSession.title')
                    ->label('Buổi học')
                    ->searchable(),
                TextColumn::make('teacherProfile.teacher_code')
                    ->label('Giáo viên')
                    ->searchable(),
                TextColumn::make('comment_type')
                    ->label('Loại')
                    ->searchable(),
                TextColumn::make('visibility')
                    ->label('Hiển thị')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable(),
                TextColumn::make('rating')
                    ->label('Đánh giá')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('commented_at')
                    ->label('Ngày nhận xét')
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
