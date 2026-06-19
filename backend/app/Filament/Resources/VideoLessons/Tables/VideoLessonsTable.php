<?php

namespace App\Filament\Resources\VideoLessons\Tables;

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

class VideoLessonsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Video')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('course.name')
                    ->label('Khóa liên quan')
                    ->searchable(),
                TextColumn::make('video_provider')
                    ->label('Nền tảng')
                    ->badge(),
                TextColumn::make('duration_minutes')
                    ->label('Phút')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('access_level')
                    ->label('Quyền xem')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi lead',
                        'student' => 'Học viên',
                        'internal' => 'Nội bộ',
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
            ])
            ->filters([
                SelectFilter::make('access_level')
                    ->label('Quyền xem')
                    ->options([
                        'public' => 'Công khai',
                        'lead_magnet' => 'Đổi lead',
                        'student' => 'Học viên',
                        'internal' => 'Nội bộ',
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
