<?php

namespace App\Filament\Resources\ConsultationActivities\Tables;

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

class ConsultationActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.full_name')
                    ->label('Lead')
                    ->searchable(),
                TextColumn::make('activity_type')
                    ->label('Loại')
                    ->badge(),
                TextColumn::make('subject')
                    ->label('Tiêu đề')
                    ->searchable(),
                TextColumn::make('outcome')
                    ->label('Kết quả')
                    ->badge(),
                TextColumn::make('createdBy.name')
                    ->label('Người ghi')
                    ->searchable(),
                TextColumn::make('activity_at')
                    ->label('Thời điểm')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('next_follow_up_at')
                    ->label('Follow-up')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('activity_type')
                    ->label('Loại hoạt động')
                    ->options([
                        'call' => 'Gọi điện',
                        'zalo' => 'Zalo',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'note' => 'Ghi chú',
                        'trial' => 'Học thử',
                        'other' => 'Khác',
                    ]),
                SelectFilter::make('outcome')
                    ->label('Kết quả')
                    ->options([
                        'interested' => 'Quan tâm',
                        'need_follow_up' => 'Cần follow-up',
                        'trial_booked' => 'Đã hẹn học thử',
                        'registered' => 'Đã đăng ký',
                        'not_interested' => 'Không quan tâm',
                        'no_answer' => 'Không nghe máy',
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
            ])
            ->defaultSort('activity_at', 'desc');
    }
}
