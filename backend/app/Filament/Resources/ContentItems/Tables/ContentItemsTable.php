<?php

namespace App\Filament\Resources\ContentItems\Tables;

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

class ContentItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Tiêu đề')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->searchable(),
                TextColumn::make('course.name')
                    ->label('Khóa liên quan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('content_type')
                    ->label('Loại')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'article' => 'Bài viết',
                        'checklist' => 'Checklist',
                        'case_study' => 'Case study',
                        'prompt_library' => 'Prompt library',
                        'news' => 'Tin tức',
                        default => $state ?? '-',
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'published' => 'success',
                        'archived' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
                        default => 'Nháp',
                    }),
                TextColumn::make('published_at')
                    ->label('Xuất bản')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('content_type')
                    ->label('Loại')
                    ->options([
                        'article' => 'Bài viết',
                        'checklist' => 'Checklist',
                        'case_study' => 'Case study',
                        'prompt_library' => 'Prompt library',
                        'news' => 'Tin tức',
                    ]),
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'draft' => 'Nháp',
                        'published' => 'Đã xuất bản',
                        'archived' => 'Lưu trữ',
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
