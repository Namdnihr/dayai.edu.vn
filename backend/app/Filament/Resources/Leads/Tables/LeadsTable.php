<?php

namespace App\Filament\Resources\Leads\Tables;

use App\Models\CustomerAccount;
use App\Models\GuardianRelation;
use App\Models\Lead;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\Person;
use App\Models\StudentProfile;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class LeadsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('KhÃ¡ch hÃ ng')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('SÄT')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company_name')
                    ->label('CÃ´ng ty')
                    ->searchable(),
                TextColumn::make('lead_type')
                    ->label('Loáº¡i khÃ¡ch')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'parent' => 'Phá»¥ huynh',
                        'student' => 'Sinh viÃªn',
                        'business_owner' => 'Chá»§ DN',
                        'company' => 'CÃ´ng ty',
                        default => 'ChÆ°a rÃµ',
                    }),
                TextColumn::make('status')
                    ->label('Tráº¡ng thÃ¡i')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'registered' => 'success',
                        'trial_scheduled' => 'info',
                        'lost', 'not_fit' => 'danger',
                        'duplicate' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'new' => 'Má»›i',
                        'contacting' => 'Äang liÃªn há»‡',
                        'consulting' => 'Äang tÆ° váº¥n',
                        'trial_scheduled' => 'Há»c thá»­',
                        'registered' => 'ÄÃ£ Ä‘Äƒng kÃ½',
                        'not_fit' => 'KhÃ´ng phÃ¹ há»£p',
                        'lost' => 'Máº¥t liÃªn há»‡',
                        'duplicate' => 'TrÃ¹ng',
                        default => '-',
                    }),
                TextColumn::make('priority')
                    ->label('Æ¯u tiÃªn')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        'low' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('temperature')
                    ->label('Độ nóng')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'hot' => 'danger',
                        'cold' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'hot' => 'Nóng',
                        'cold' => 'Lạnh',
                        default => 'Ấm',
                    }),
                TextColumn::make('expected_value_vnd')
                    ->label('Cơ hội')
                    ->money('VND')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),                TextColumn::make('source.name')
                    ->label('Nguá»“n')
                    ->searchable(),
                TextColumn::make('utm_campaign')
                    ->label('Campaign')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('course_slug')
                    ->label('Landing')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('affiliate_code')
                    ->label('Affiliate')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('assignedUser.name')
                    ->label('TÆ° váº¥n viÃªn')
                    ->searchable(),
                TextColumn::make('next_follow_up_at')
                    ->label('Follow-up')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state): string => $state && $state->isPast() ? 'danger' : 'gray'),
                TextColumn::make('created_at')
                    ->label('NgÃ y táº¡o')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Tráº¡ng thÃ¡i')
                    ->options([
                        'new' => 'Má»›i',
                        'contacting' => 'Äang liÃªn há»‡',
                        'consulting' => 'Äang tÆ° váº¥n',
                        'trial_scheduled' => 'ÄÃ£ háº¹n há»c thá»­',
                        'registered' => 'ÄÃ£ Ä‘Äƒng kÃ½',
                        'not_fit' => 'KhÃ´ng phÃ¹ há»£p',
                        'lost' => 'Máº¥t liÃªn há»‡',
                        'duplicate' => 'TrÃ¹ng',
                    ]),
                SelectFilter::make('lead_type')
                    ->label('Loáº¡i khÃ¡ch')
                    ->options([
                        'parent' => 'Phá»¥ huynh',
                        'student' => 'Sinh viÃªn',
                        'business_owner' => 'Chá»§ doanh nghiá»‡p',
                        'company' => 'CÃ´ng ty',
                        'unknown' => 'ChÆ°a rÃµ',
                    ]),
                SelectFilter::make('temperature')
                    ->label('Độ nóng lead')
                    ->options([
                        'hot' => 'Nóng',
                        'warm' => 'Ấm',
                        'cold' => 'Lạnh',
                    ]),
                SelectFilter::make('lost_reason_type')
                    ->label('Lý do mất')
                    ->options([
                        'price' => 'Học phí',
                        'schedule' => 'Lịch học',
                        'no_response' => 'Không phản hồi',
                        'not_ready' => 'Chưa sẵn sàng',
                        'competitor' => 'Chọn đơn vị khác',
                        'not_fit' => 'Không đúng đối tượng',
                        'other' => 'Khác',
                    ]),
                Filter::make('overdue_follow_up')
                    ->label('Quá hạn follow-up')
                    ->query(fn ($query) => $query
                        ->whereNotNull('next_follow_up_at')
                        ->where('next_follow_up_at', '<', now())
                        ->whereNotIn('status', ['registered', 'lost', 'not_fit', 'duplicate'])),                SelectFilter::make('lead_source_id')
                    ->label('Nguá»“n')
                    ->relationship('source', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('assigned_user_id')
                    ->label('TÆ° váº¥n viÃªn')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('convertLead')
                    ->label('Chuyá»ƒn há»“ sÆ¡')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('success')
                    ->visible(fn (Lead $record): bool => $record->status !== 'registered')
                    ->form([
                        TextInput::make('student_full_name')
                            ->label('TÃªn ngÆ°á»i há»c')
                            ->helperText('Vá»›i phá»¥ huynh/cÃ´ng ty, nháº­p tÃªn con hoáº·c nhÃ¢n sá»± há»c. Náº¿u Ä‘á»ƒ trá»‘ng sáº½ dÃ¹ng tÃªn lead.')
                            ->maxLength(255),
                        TextInput::make('organization_name')
                            ->label('TÃªn doanh nghiá»‡p')
                            ->helperText('DÃ¹ng cho lead cÃ´ng ty hoáº·c chá»§ doanh nghiá»‡p.')
                            ->maxLength(255),
                        TextInput::make('job_title')
                            ->label('Chá»©c danh')
                            ->maxLength(255),
                    ])
                    ->modalHeading('Chuyá»ƒn lead thÃ nh há»“ sÆ¡ tháº­t')
                    ->modalSubmitActionLabel('Chuyá»ƒn há»“ sÆ¡')
                    ->successNotificationTitle('ÄÃ£ chuyá»ƒn lead thÃ nh há»“ sÆ¡')
                    ->action(fn (Lead $record, array $data) => self::convertLead($record, $data)),
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
            ->defaultSort('next_follow_up_at', 'asc');
    }

    protected static function convertLead(Lead $lead, array $data): void
    {
        DB::transaction(function () use ($lead, $data): void {
            $buyerPerson = self::firstOrCreatePersonFromLead($lead);
            $studentPerson = $buyerPerson;
            $organization = $lead->organization;
            $studentFullName = filled($data['student_full_name'] ?? null) ? $data['student_full_name'] : $lead->full_name;

            if ($lead->lead_type === 'parent') {
                $studentPerson = Person::query()->firstOrCreate(
                    [
                        'tenant_id' => $lead->tenant_id,
                        'full_name' => $studentFullName,
                        'phone' => null,
                    ],
                    [
                        'branch_id' => $lead->branch_id,
                        'display_name' => $studentFullName,
                        'notes' => "Táº¡o tá»« lead phá»¥ huynh {$lead->full_name}",
                        'metadata' => ['source_lead_id' => $lead->id],
                    ],
                );
            }

            if (in_array($lead->lead_type, ['business_owner', 'company'], true)) {
                $organizationName = filled($data['organization_name'] ?? null)
                    ? $data['organization_name']
                    : ($lead->company_name ?: "{$lead->full_name} Company");

                $organization = Organization::query()->firstOrCreate(
                    [
                        'tenant_id' => $lead->tenant_id,
                        'name' => $organizationName,
                    ],
                    [
                        'branch_id' => $lead->branch_id,
                        'organization_type' => 'company',
                        'phone' => $lead->phone,
                        'email' => $lead->email,
                        'status' => 'active',
                        'notes' => 'Táº¡o khi chuyá»ƒn lead.',
                        'metadata' => ['source_lead_id' => $lead->id],
                    ],
                );

                OrganizationContact::query()->firstOrCreate(
                    [
                        'tenant_id' => $lead->tenant_id,
                        'organization_id' => $organization->id,
                        'person_id' => $buyerPerson->id,
                    ],
                    [
                        'contact_role' => $lead->lead_type === 'company' ? 'hr' : 'owner',
                        'job_title' => $data['job_title'] ?? null,
                        'is_primary' => true,
                        'status' => 'active',
                        'notes' => 'LiÃªn há»‡ táº¡o tá»« lead.',
                    ],
                );

                if ($lead->lead_type === 'company') {
                    $studentPerson = Person::query()->firstOrCreate(
                        [
                            'tenant_id' => $lead->tenant_id,
                            'full_name' => $studentFullName,
                            'phone' => null,
                        ],
                        [
                            'branch_id' => $lead->branch_id,
                            'display_name' => $studentFullName,
                            'notes' => "NhÃ¢n sá»± há»c tá»« lead cÃ´ng ty {$lead->company_name}",
                            'metadata' => ['source_lead_id' => $lead->id],
                        ],
                    );
                }
            }

            $studentProfile = StudentProfile::query()->firstOrCreate(
                [
                    'tenant_id' => $lead->tenant_id,
                    'person_id' => $studentPerson->id,
                ],
                [
                    'branch_id' => $lead->branch_id,
                    'student_code' => self::nextCode($lead->tenant_id, 'HV', StudentProfile::class, 'student_code'),
                    'student_type' => match ($lead->lead_type) {
                        'parent' => 'child',
                        'student' => 'university_student',
                        'business_owner' => 'business_owner',
                        'company' => 'company_employee',
                        default => 'working_professional',
                    },
                    'current_company' => $organization?->name,
                    'job_title' => $data['job_title'] ?? null,
                    'organization_id' => $organization?->id,
                    'learning_goal' => $lead->learning_goal,
                    'status' => 'lead_converted',
                    'metadata' => ['source_lead_id' => $lead->id],
                ],
            );

            if ($lead->lead_type === 'parent') {
                GuardianRelation::query()->firstOrCreate(
                    [
                        'tenant_id' => $lead->tenant_id,
                        'guardian_person_id' => $buyerPerson->id,
                        'student_profile_id' => $studentProfile->id,
                    ],
                    [
                        'relation_type' => 'guardian',
                        'is_primary' => true,
                    ],
                );
            }

            CustomerAccount::query()->firstOrCreate(
                [
                    'tenant_id' => $lead->tenant_id,
                    'person_id' => $lead->lead_type === 'company' ? null : $buyerPerson->id,
                    'organization_id' => $lead->lead_type === 'company' ? $organization?->id : null,
                ],
                [
                    'branch_id' => $lead->branch_id,
                    'customer_code' => self::nextCode($lead->tenant_id, 'KH', CustomerAccount::class, 'customer_code'),
                    'account_type' => $lead->lead_type === 'company' ? 'organization' : 'individual',
                    'display_name' => $lead->lead_type === 'company' ? ($organization?->name ?? $lead->company_name ?? $lead->full_name) : $buyerPerson->full_name,
                    'phone' => $lead->phone,
                    'email' => $lead->email,
                    'billing_address' => $organization?->billing_address,
                    'tax_code' => $organization?->tax_code,
                    'status' => 'active',
                    'metadata' => ['source_lead_id' => $lead->id],
                ],
            );

            $lead->update([
                'person_id' => $buyerPerson->id,
                'organization_id' => $organization?->id,
                'status' => 'registered',
                'converted_at' => now(),
            ]);
        });
    }

    protected static function firstOrCreatePersonFromLead(Lead $lead): Person
    {
        if ($lead->person) {
            return $lead->person;
        }

        return Person::query()->firstOrCreate(
            [
                'tenant_id' => $lead->tenant_id,
                'phone' => $lead->phone,
            ],
            [
                'branch_id' => $lead->branch_id,
                'full_name' => $lead->full_name,
                'display_name' => $lead->full_name,
                'email' => $lead->email,
                'notes' => 'Táº¡o khi chuyá»ƒn lead.',
                'metadata' => ['source_lead_id' => $lead->id],
            ],
        );
    }

    protected static function nextCode(string $tenantId, string $prefix, string $modelClass, string $column): string
    {
        $nextNumber = $modelClass::query()
            ->where('tenant_id', $tenantId)
            ->count() + 1;

        do {
            $code = $prefix . '-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
            $nextNumber++;
        } while ($modelClass::query()->where('tenant_id', $tenantId)->where($column, $code)->exists());

        return $code;
    }
}
