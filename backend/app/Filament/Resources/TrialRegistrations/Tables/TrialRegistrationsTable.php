<?php

namespace App\Filament\Resources\TrialRegistrations\Tables;

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

class TrialRegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.full_name')
                    ->label('Lead')
                    ->searchable(),
                TextColumn::make('person.full_name')
                    ->label('Người đăng ký')
                    ->searchable(),
                TextColumn::make('preferred_date')
                    ->label('Ngày mong muốn')
                    ->date()
                    ->sortable(),
                TextColumn::make('preferred_time')
                    ->label('Khung giờ'),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'attended', 'converted' => 'success',
                        'scheduled' => 'info',
                        'no_show', 'cancelled' => 'danger',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'requested' => 'Mới yêu cầu',
                        'scheduled' => 'Đã xếp lịch',
                        'attended' => 'Đã tham gia',
                        'no_show' => 'Không tham gia',
                        'cancelled' => 'Đã hủy',
                        'converted' => 'Đã chuyển đổi',
                        default => '-',
                    }),
                TextColumn::make('createdBy.name')
                    ->label('Người tạo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'requested' => 'Mới yêu cầu',
                        'scheduled' => 'Đã xếp lịch',
                        'attended' => 'Đã tham gia',
                        'no_show' => 'Không tham gia',
                        'cancelled' => 'Đã hủy',
                        'converted' => 'Đã chuyển đổi',
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
            ->defaultSort('preferred_date', 'asc');
    }
}
