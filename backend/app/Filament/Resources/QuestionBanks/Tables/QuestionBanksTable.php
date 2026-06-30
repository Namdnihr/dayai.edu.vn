<?php

namespace App\Filament\Resources\QuestionBanks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionBanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Ng?n h?ng')->searchable()->sortable(),
                TextColumn::make('course.name')->label('Kh?a h?c')->searchable()->toggleable(),
                TextColumn::make('courseModule.title')->label('Module')->searchable()->toggleable(),
                TextColumn::make('videoLesson.title')->label('Video')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('bank_type')->label('Ph?m vi')->badge(),
                TextColumn::make('questions_count')->label('S? c?u')->counts('questions'),
                TextColumn::make('status')->label('Tr?ng th?i')->badge(),
                TextColumn::make('updated_at')->label('C?p nh?t')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('bank_type')->label('Ph?m vi')->options([
                    'course' => 'Theo kh?a h?c',
                    'module' => 'Theo module',
                    'video' => 'Theo video',
                    'placement' => '??u v?o',
                    'final' => 'Cu?i kh?a',
                    'general' => 'D?ng chung',
                ]),
                SelectFilter::make('status')->label('Tr?ng th?i')->options([
                    'active' => '?ang d?ng',
                    'draft' => 'Nh?p',
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
