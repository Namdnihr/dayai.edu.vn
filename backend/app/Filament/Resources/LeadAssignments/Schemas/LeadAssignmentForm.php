<?php

namespace App\Filament\Resources\LeadAssignments\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->label('Đơn vị')
                    ->relationship('tenant', 'name')
                    ->default(fn () => Tenant::query()->value('id'))
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('lead_id')
                    ->label('Lead')
                    ->relationship('lead', 'full_name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('assigned_to_user_id')
                    ->label('Tư vấn viên')
                    ->relationship('assignedToUser', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('assigned_by_user_id')
                    ->label('Người phân công')
                    ->relationship('assignedByUser', 'name')
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('assigned_at')
                    ->label('Thời điểm phân công')
                    ->default(now())
                    ->required(),
                DateTimePicker::make('unassigned_at')
                    ->label('Thời điểm kết thúc'),
                Textarea::make('note')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }
}
