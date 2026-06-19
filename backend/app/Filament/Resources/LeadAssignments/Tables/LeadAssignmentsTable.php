<?php

namespace App\Filament\Resources\LeadAssignments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LeadAssignmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('lead.full_name')
                    ->label('Lead')
                    ->searchable(),
                TextColumn::make('assignedToUser.name')
                    ->label('Tư vấn viên')
                    ->searchable(),
                TextColumn::make('assignedByUser.name')
                    ->label('Người phân công')
                    ->searchable(),
                TextColumn::make('assigned_at')
                    ->label('Phân công lúc')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('unassigned_at')
                    ->label('Kết thúc lúc')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('assigned_at', 'desc');
    }
}
