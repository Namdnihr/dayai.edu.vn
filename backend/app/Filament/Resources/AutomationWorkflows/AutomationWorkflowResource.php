<?php

namespace App\Filament\Resources\AutomationWorkflows;

use App\Filament\Concerns\AuthorizesResourceAccess;
use App\Filament\Resources\AutomationWorkflows\Pages\CreateAutomationWorkflow;
use App\Filament\Resources\AutomationWorkflows\Pages\EditAutomationWorkflow;
use App\Filament\Resources\AutomationWorkflows\Pages\ListAutomationWorkflows;
use App\Filament\Resources\AutomationWorkflows\Pages\ViewAutomationWorkflow;
use App\Models\AutomationWorkflow;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AutomationWorkflowResource extends Resource
{
    use AuthorizesResourceAccess;

    protected static ?string $model = AutomationWorkflow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bolt;

    protected static ?string $navigationLabel = 'Kịch bản tự động';

    protected static ?string $modelLabel = 'kịch bản tự động';

    protected static ?string $pluralModelLabel = 'kịch bản tự động';

    protected static string|UnitEnum|null $navigationGroup = 'Vận hành trung tâm';

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('tenant_id')
                ->label('Đơn vị')
                ->relationship('tenant', 'name')
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('name')
                ->label('Tên kịch bản')
                ->required()
                ->maxLength(255),
            TextInput::make('code')
                ->label('Mã kịch bản')
                ->required()
                ->maxLength(120),
            Select::make('trigger_type')
                ->label('Sự kiện kích hoạt')
                ->options(self::triggerOptions())
                ->searchable()
                ->required(),
            Select::make('audience_type')
                ->label('Nhóm nhận')
                ->options([
                    'lead' => 'Lead',
                    'student' => 'Học viên',
                    'guardian' => 'Phụ huynh',
                    'payer' => 'Người thanh toán',
                    'staff' => 'Nhân sự nội bộ',
                ])
                ->required(),
            Select::make('channel')
                ->label('Kênh mặc định')
                ->options([
                    'email' => 'Email',
                    'portal' => 'Portal',
                    'sms' => 'SMS',
                    'zalo' => 'Zalo',
                ])
                ->default('email')
                ->required(),
            Select::make('status')
                ->label('Trạng thái')
                ->options([
                    'active' => 'Đang chạy',
                    'paused' => 'Tạm dừng',
                    'draft' => 'Nháp',
                ])
                ->default('active')
                ->required(),
            TextInput::make('cooldown_hours')
                ->label('Chống gửi trùng trong số giờ')
                ->numeric()
                ->default(24)
                ->required(),
            KeyValue::make('conditions')
                ->label('Điều kiện chạy')
                ->helperText('Ví dụ: within_hours=24, within_days=7, limit=50.')
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Kịch bản')->searchable()->sortable(),
                TextColumn::make('trigger_type')->label('Sự kiện')->badge()->searchable(),
                TextColumn::make('audience_type')->label('Nhóm nhận')->badge(),
                TextColumn::make('channel')->label('Kênh')->badge(),
                TextColumn::make('status')->label('Trạng thái')->badge()->sortable(),
                TextColumn::make('cooldown_hours')->label('Cooldown')->suffix(' giờ')->sortable(),
                TextColumn::make('last_run_at')->label('Lần chạy gần nhất')->dateTime()->sortable(),
                TextColumn::make('messages_count')->label('Mẫu')->counts('messages')->sortable(),
                TextColumn::make('logs_count')->label('Log')->counts('logs')->sortable(),
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
            'index' => ListAutomationWorkflows::route('/'),
            'create' => CreateAutomationWorkflow::route('/create'),
            'view' => ViewAutomationWorkflow::route('/{record}'),
            'edit' => EditAutomationWorkflow::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function triggerOptions(): array
    {
        return [
            'lead.created' => 'Lead mới / đăng ký tư vấn',
            'trial_registration.created' => 'Đăng ký học thử',
            'order.created' => 'Mua khóa học / tạo đơn',
            'payment.completed' => 'Thanh toán thành công',
            'enrollment.created' => 'Ghi danh học viên',
            'class_session.upcoming' => 'Nhắc lịch học sắp tới',
            'invoice.due_soon' => 'Nhắc học phí/công nợ',
            'progress_report.published' => 'Báo cáo tiến độ định kỳ',
            'lead.follow_up_overdue' => 'Lead quá hạn follow-up',
        ];
    }
}
