<?php

namespace App\Filament\Resources\AutomationLogs;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AutomationLogs\Pages\ListAutomationLogs;
use App\Filament\Resources\AutomationLogs\Pages\ViewAutomationLog;
use App\Models\AutomationLog;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AutomationLogResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = AutomationLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Log email tự động';

    protected static ?string $modelLabel = 'log email tự động';

    protected static ?string $pluralModelLabel = 'log email tự động';

    protected static string|UnitEnum|null $navigationGroup = 'Vận hành trung tâm';

    protected static ?int $navigationSort = 72;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Thông tin gửi')
                ->schema([
                    TextEntry::make('workflow.name')->label('Kịch bản'),
                    TextEntry::make('trigger_type')->label('Sự kiện'),
                    TextEntry::make('audience_type')->label('Nhóm nhận'),
                    TextEntry::make('channel')->label('Kênh'),
                    TextEntry::make('status')->label('Trạng thái')->badge(),
                    TextEntry::make('sent_at')->label('Đã gửi lúc')->dateTime(),
                    TextEntry::make('error_message')->label('Lỗi')->placeholder('-')->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Payload')
                ->schema([
                    TextEntry::make('payload')
                        ->label('Dữ liệu')
                        ->formatStateUsing(fn ($state): string => json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?: '-')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('workflow.name')->label('Kịch bản')->searchable()->sortable(),
                TextColumn::make('trigger_type')->label('Sự kiện')->badge()->searchable(),
                TextColumn::make('payload.recipient_email')->label('Email')->searchable(),
                TextColumn::make('channel')->label('Kênh')->badge(),
                TextColumn::make('status')->label('Trạng thái')->badge()->sortable(),
                TextColumn::make('error_message')->label('Lỗi')->limit(40)->toggleable(),
                TextColumn::make('sent_at')->label('Đã gửi')->dateTime()->sortable(),
                TextColumn::make('created_at')->label('Tạo lúc')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'sent' => 'Đã gửi',
                        'skipped' => 'Bỏ qua',
                        'failed' => 'Lỗi',
                    ]),
                SelectFilter::make('channel')
                    ->label('Kênh')
                    ->options([
                        'email' => 'Email',
                        'portal' => 'Portal',
                        'sms' => 'SMS',
                        'zalo' => 'Zalo',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAutomationLogs::route('/'),
            'view' => ViewAutomationLog::route('/{record}'),
        ];
    }
}
