<?php

namespace App\Filament\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('certificate_code')
                    ->label('Mã chứng chỉ')
                    ->searchable(),
                TextColumn::make('studentProfile.student_code')
                    ->label('Học viên')
                    ->searchable(),
                TextColumn::make('course.name')
                    ->label('Khóa học')
                    ->searchable(),
                TextColumn::make('classGroup.name')
                    ->label('Lớp')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Tên chứng chỉ')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->searchable(),
                TextColumn::make('final_score')
                    ->label('Điểm')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('grade')
                    ->label('Xếp loại')
                    ->searchable(),
                TextColumn::make('issued_at')
                    ->label('Ngày cấp')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Hết hạn')
                    ->date()
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
