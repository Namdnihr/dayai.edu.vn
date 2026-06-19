<?php

namespace App\Filament\Resources\LeadSources\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LeadSourcesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nguồn')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Mã')
                    ->searchable(),
                TextColumn::make('source_type')
                    ->label('Loại')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label('Đang dùng')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('source_type')
                    ->label('Loại nguồn')
                    ->options([
                        'manual' => 'Nhập thủ công',
                        'website' => 'Website',
                        'social' => 'Mạng xã hội',
                        'referral' => 'Giới thiệu',
                        'event' => 'Sự kiện',
                        'chatbot' => 'Chatbot',
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
