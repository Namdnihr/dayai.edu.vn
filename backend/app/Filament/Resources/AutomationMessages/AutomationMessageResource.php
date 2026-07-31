<?php

namespace App\Filament\Resources\AutomationMessages;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AutomationMessages\Pages\CreateAutomationMessage;
use App\Filament\Resources\AutomationMessages\Pages\EditAutomationMessage;
use App\Filament\Resources\AutomationMessages\Pages\ListAutomationMessages;
use App\Filament\Resources\AutomationMessages\Pages\ViewAutomationMessage;
use App\Models\AutomationMessage;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AutomationMessageResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = AutomationMessage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Envelope;

    protected static ?string $navigationLabel = 'Mẫu email tự động';

    protected static ?string $modelLabel = 'mẫu email tự động';

    protected static ?string $pluralModelLabel = 'mẫu email tự động';

    protected static string|UnitEnum|null $navigationGroup = 'Tự động hóa';

    protected static ?int $navigationSort = 71;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')
                ->label('Đơn vị')
                ->relationship('tenant', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('automation_workflow_id')
                ->label('Kịch bản')
                ->relationship('workflow', 'name')
                ->searchable()
                ->preload()
                ->required(),
            Select::make('message_type')
                ->label('Loại nội dung')
                ->options([
                    'notification' => 'Thông báo',
                    'transactional' => 'Transactional email',
                    'reminder' => 'Nhắc việc',
                ])
                ->default('notification')
                ->required(),
            Select::make('channel')
                ->label('Kênh gửi')
                ->options([
                    'email' => 'Email',
                    'portal' => 'Portal',
                    'sms' => 'SMS',
                    'zalo' => 'Zalo',
                ])
                ->default('email')
                ->required(),
            TextInput::make('title_template')
                ->label('Tiêu đề')
                ->helperText('Có thể dùng biến như {{name}}, {{course}}, {{amount}}, {{starts_at}}.')
                ->required()
                ->maxLength(255),
            Textarea::make('body_template')
                ->label('Nội dung')
                ->rows(8)
                ->required()
                ->columnSpanFull(),
            Select::make('priority')
                ->label('Ưu tiên')
                ->options([
                    'low' => 'Thấp',
                    'normal' => 'Thường',
                    'high' => 'Cao',
                    'urgent' => 'Khẩn',
                ])
                ->default('normal')
                ->required(),
            Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'active' => 'Đang dùng',
                    'paused' => 'Tạm dừng',
                    'draft' => 'Nháp',
                ])
                ->default('active')
                ->required(),
            KeyValue::make('metadata')
                ->label('Metadata')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workflow.name')->label('Kịch bản')->searchable()->sortable(),
                TextColumn::make('title_template')->label('Tiêu đề')->searchable()->limit(54),
                TextColumn::make('channel')->label('Kênh')->badge(),
                TextColumn::make('priority')->label('Ưu tiên')->badge(),
                TextColumn::make('status')->label('Trạng thái')->badge()->sortable(),
                TextColumn::make('updated_at')->label('Cập nhật')->dateTime()->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAutomationMessages::route('/'),
            'create' => CreateAutomationMessage::route('/create'),
            'view' => ViewAutomationMessage::route('/{record}'),
            'edit' => EditAutomationMessage::route('/{record}/edit'),
        ];
    }
}
