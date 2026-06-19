<?php

namespace App\Filament\Resources\Organizations\Tables;

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

class OrganizationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tenant.name')
                    ->label('Đơn vị')
                    ->searchable(),
                TextColumn::make('branch.name')
                    ->label('Cơ sở')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Doanh nghiệp')
                    ->searchable(),
                TextColumn::make('short_name')
                    ->label('Tên tắt')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('organization_type')
                    ->label('Loại')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'company' => 'Công ty',
                        'school' => 'Trường học',
                        'partner' => 'Đối tác',
                        'vendor' => 'Nhà cung cấp',
                        default => '-',
                    }),
                TextColumn::make('tax_code')
                    ->label('MST')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('industry')
                    ->label('Ngành')
                    ->searchable(),
                TextColumn::make('company_size')
                    ->label('Quy mô')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('SĐT')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('website')
                    ->label('Website')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'active' => 'Đang hợp tác',
                        'inactive' => 'Tạm ngưng',
                        'prospect' => 'Tiềm năng',
                        default => '-',
                    })
                    ->searchable(),
                TextColumn::make('created_by_id')
                    ->label('Người tạo')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_by_id')
                    ->label('Người cập nhật')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->label('Ngày xóa')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'prospect' => 'Tiềm năng',
                        'active' => 'Đang hợp tác',
                        'inactive' => 'Tạm ngưng',
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
