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
                    ->label('Khách hàng')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('SĐT')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('company_name')
                    ->label('Công ty')
                    ->searchable(),
                TextColumn::make('lead_type')
                    ->label('Loại khách')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'parent' => 'Phụ huynh',
                        'student' => 'Sinh viên',
                        'business_owner' => 'Chủ DN',
                        'company' => 'Công ty',
                        default => 'Chưa rõ',
                    }),
                TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'registered' => 'success',
                        'trial_scheduled' => 'info',
                        'lost', 'not_fit' => 'danger',
                        'duplicate' => 'gray',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'new' => 'Mới',
                        'contacting' => 'Đang liên hệ',
                        'consulting' => 'Đang tư vấn',
                        'trial_scheduled' => 'Học thử',
                        'registered' => 'Đã đăng ký',
                        'not_fit' => 'Không phù hợp',
                        'lost' => 'Mất liên hệ',
                        'duplicate' => 'Trùng',
                        default => '-',
                    }),
                TextColumn::make('priority')
                    ->label('Ưu tiên')
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
                    ->label('Nguồn')
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
                    ->label('Tư vấn viên')
                    ->searchable(),
                TextColumn::make('next_follow_up_at')
                    ->label('Follow-up')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state): string => $state && $state->isPast() ? 'danger' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Ngày tạo')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'new' => 'Mới',
                        'contacting' => 'Đang liên hệ',
                        'consulting' => 'Đang tư vấn',
                        'trial_scheduled' => 'Đã hẹn học thử',
                        'registered' => 'Đã đăng ký',
                        'not_fit' => 'Không phù hợp',
                        'lost' => 'Mất liên hệ',
                        'duplicate' => 'Trùng',
                    ]),
                SelectFilter::make('lead_type')
                    ->label('Loại khách')
                    ->options([
                        'parent' => 'Phụ huynh',
                        'student' => 'Sinh viên',
                        'business_owner' => 'Chủ doanh nghiệp',
                        'company' => 'Công ty',
                        'unknown' => 'Chưa rõ',
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
                    ->label('Nguồn')
                    ->relationship('source', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('assigned_user_id')
                    ->label('Tư vấn viên')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                Action::make('convertLead')
                    ->label('Chuyển hồ sơ')
                    ->icon('heroicon-o-arrow-path-rounded-square')
                    ->color('success')
                    ->visible(fn (Lead $record): bool => $record->status !== 'registered')
                    ->form([
                        TextInput::make('student_full_name')
                            ->label('Tên người học')
                            ->helperText('Với phụ huynh/công ty, nhập tên con hoặc nhân sự học. Nếu để trống sẽ dùng tên lead.')
                            ->maxLength(255),
                        TextInput::make('organization_name')
                            ->label('Tên doanh nghiệp')
                            ->helperText('Dùng cho lead công ty hoặc chủ doanh nghiệp.')
                            ->maxLength(255),
                        TextInput::make('job_title')
                            ->label('Chức danh')
                            ->maxLength(255),
                    ])
                    ->modalHeading('Chuyển lead thành hồ sơ thật')
                    ->modalSubmitActionLabel('Chuyển hồ sơ')
                    ->successNotificationTitle('Đã chuyển lead thành hồ sơ')
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
                        'notes' => "Tạo từ lead phụ huynh {$lead->full_name}",
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
                        'notes' => 'Tạo khi chuyển lead.',
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
                        'notes' => 'Liên hệ tạo từ lead.',
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
                            'notes' => "Nhân sự học từ lead công ty {$lead->company_name}",
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
                'notes' => 'Tạo khi chuyển lead.',
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
