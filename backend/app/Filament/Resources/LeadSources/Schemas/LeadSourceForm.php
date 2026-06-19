<?php

namespace App\Filament\Resources\LeadSources\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeadSourceForm
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
                TextInput::make('name')
                    ->label('Tên nguồn')
                    ->maxLength(255)
                    ->required(),
                TextInput::make('code')
                    ->label('Mã nguồn')
                    ->maxLength(80)
                    ->required(),
                Select::make('source_type')
                    ->label('Loại nguồn')
                    ->options([
                        'manual' => 'Nhập thủ công',
                        'website' => 'Website',
                        'social' => 'Mạng xã hội',
                        'referral' => 'Giới thiệu',
                        'event' => 'Sự kiện',
                        'chatbot' => 'Chatbot',
                    ])
                    ->default('manual')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Đang dùng')
                    ->default(true),
                Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
            ]);
    }
}
