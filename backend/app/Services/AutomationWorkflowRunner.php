<?php

namespace App\Services;

use App\Mail\TransactionalAutomationMail;
use App\Models\AutomationLog;
use App\Models\AutomationMessage;
use App\Models\AutomationWorkflow;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProgressReport;
use App\Models\Tenant;
use App\Models\TrialRegistration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Throwable;

class AutomationWorkflowRunner
{
    /**
     * @return array<string, int>
     */
    public function run(?string $triggerType = null): array
    {
        $summary = [
            'workflow_count' => 0,
            'processed_count' => 0,
            'sent_count' => 0,
            'skipped_count' => 0,
            'failed_count' => 0,
        ];

        $workflows = AutomationWorkflow::query()
            ->with(['messages' => fn ($query) => $query->where('status', 'active')])
            ->where('status', 'active')
            ->when($triggerType, fn ($query) => $query->where('trigger_type', $triggerType))
            ->get();

        foreach ($workflows as $workflow) {
            $summary['workflow_count']++;

            foreach ($this->subjectsForWorkflow($workflow) as $subject) {
                $summary['processed_count']++;

                foreach ($workflow->messages as $message) {
                    foreach ($this->sendMessage($workflow, $message, $subject) as $status => $count) {
                        $summary[$status . '_count'] += $count;
                    }
                }
            }

            $workflow->forceFill(['last_run_at' => now()])->save();
        }

        return $summary;
    }

    /**
     * @return Collection<int, Model>
     */
    private function subjectsForWorkflow(AutomationWorkflow $workflow): Collection
    {
        return match ($workflow->trigger_type) {
            'lead.created' => Lead::query()
                ->with(['person', 'organization'])
                ->where('tenant_id', $workflow->tenant_id)
                ->whereIn('status', ['new', 'trial_requested'])
                ->where('created_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->latest()
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'lead.follow_up_overdue' => Lead::query()
                ->with(['person', 'organization'])
                ->where('tenant_id', $workflow->tenant_id)
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<', now())
                ->whereNotIn('status', ['registered', 'lost', 'not_fit', 'duplicate'])
                ->orderBy('next_follow_up_at')
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'trial_registration.created' => TrialRegistration::query()
                ->with(['lead.person', 'lead.organization', 'person'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('created_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->latest()
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'order.created' => Order::query()
                ->with(['customerAccount.person', 'customerAccount.organization', 'lead', 'items.course'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('created_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->whereIn('status', $workflow->conditions['statuses'] ?? ['confirmed', 'partially_paid', 'paid'])
                ->latest()
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'payment.completed' => Payment::query()
                ->with(['invoice', 'order', 'customerAccount.person', 'customerAccount.organization'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('status', 'completed')
                ->where('paid_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->latest('paid_at')
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'enrollment.created' => Enrollment::query()
                ->with(['studentProfile.person', 'studentProfile.guardians.guardianPerson', 'course', 'classGroup'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('created_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->latest()
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'class_session.upcoming' => ClassSession::query()
                ->with(['classGroup.course', 'classGroup.enrollments.studentProfile.person', 'classGroup.enrollments.studentProfile.guardians.guardianPerson'])
                ->where('tenant_id', $workflow->tenant_id)
                ->whereBetween('starts_at', [
                    now(),
                    now()->addHours($workflow->conditions['within_hours'] ?? 24),
                ])
                ->where('status', 'scheduled')
                ->orderBy('starts_at')
                ->limit($workflow->conditions['limit'] ?? 30)
                ->get(),
            'invoice.due_soon' => Invoice::query()
                ->with(['customerAccount.person', 'customerAccount.organization'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('balance_vnd', '>', 0)
                ->whereDate('due_date', '<=', now()->addDays($workflow->conditions['within_days'] ?? 3)->toDateString())
                ->whereIn('status', ['issued', 'partially_paid'])
                ->orderBy('due_date')
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'progress_report.published' => ProgressReport::query()
                ->with(['studentProfile.person', 'studentProfile.guardians.guardianPerson', 'course', 'classGroup', 'teacherProfile.person'])
                ->where('tenant_id', $workflow->tenant_id)
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '>=', now()->subDays($workflow->conditions['within_days'] ?? 7))
                ->latest('published_at')
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            default => collect(),
        };
    }

    /**
     * @return array{sent: int, skipped: int, failed: int}
     */
    private function sendMessage(AutomationWorkflow $workflow, AutomationMessage $message, Model $subject): array
    {
        $results = ['sent' => 0, 'skipped' => 0, 'failed' => 0];
        $recipients = $this->resolveRecipients($workflow, $subject);

        if ($recipients->isEmpty()) {
            $this->writeAutomationLog($workflow, $message, $subject, $this->fallbackRecipient(), 'skipped', null, 'Không tìm thấy người nhận phù hợp.');
            $results['skipped']++;

            return $results;
        }

        foreach ($recipients as $recipient) {
            $status = $this->sendMessageToRecipient($workflow, $message, $subject, $recipient);
            $results[$status]++;
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $recipient
     */
    private function sendMessageToRecipient(AutomationWorkflow $workflow, AutomationMessage $message, Model $subject, array $recipient): string
    {
        $dedupe = $this->dedupeKeys($workflow, $subject, $recipient);
        $existingLog = AutomationLog::query()->where($dedupe)->first();

        if ($existingLog && $existingLog->created_at?->greaterThan(now()->subHours($workflow->cooldown_hours))) {
            return 'skipped';
        }

        $title = $this->renderTemplate($message->title_template, $subject, $recipient);
        $body = $this->renderTemplate($message->body_template, $subject, $recipient);

        try {
            $notification = Notification::query()->create([
                'tenant_id' => $workflow->tenant_id,
                'person_id' => $recipient['person_id'],
                'student_profile_id' => $recipient['student_profile_id'],
                'organization_id' => $recipient['organization_id'],
                'audience_type' => $workflow->audience_type,
                'notification_type' => $this->notificationTypeFor($workflow->trigger_type),
                'channel' => $message->channel,
                'title' => $title,
                'body' => $body,
                'status' => 'published',
                'priority' => $message->priority,
                'published_at' => now(),
                'metadata' => [
                    'automation_workflow_id' => $workflow->id,
                    'automation_message_id' => $message->id,
                    'subject_type' => $subject::class,
                    'subject_id' => $subject->getKey(),
                    'recipient_email' => $recipient['email'],
                ],
            ]);

            if ($message->channel === 'email') {
                if (blank($recipient['email'])) {
                    $this->writeAutomationLog($workflow, $message, $subject, $recipient, 'skipped', $notification, 'Người nhận chưa có email.');

                    return 'skipped';
                }

                Mail::to($recipient['email'], $recipient['name'])
                    ->send(new TransactionalAutomationMail($title, $body, [
                        'recipient_name' => $recipient['name'],
                        'audience_type' => $workflow->audience_type,
                        'trigger_type' => $workflow->trigger_type,
                        'priority' => $message->priority,
                    ]));
            }

            $this->writeAutomationLog($workflow, $message, $subject, $recipient, 'sent', $notification);

            return 'sent';
        } catch (Throwable $exception) {
            $this->writeAutomationLog($workflow, $message, $subject, $recipient, 'failed', null, $exception->getMessage(), [
                'title' => $title,
                'body' => $body,
            ]);

            return 'failed';
        }
    }

    /**
     * @return Collection<int, array{type: string|null, id: string|null, person_id: string|null, student_profile_id: string|null, organization_id: string|null, email: string|null, name: string|null}>
     */
    private function resolveRecipients(AutomationWorkflow $workflow, Model $subject): Collection
    {
        $recipients = collect();

        if ($subject instanceof Lead) {
            $recipients->push($this->recipient(
                $subject->person_id ? 'person' : 'lead',
                $subject->person_id ?? $subject->id,
                $subject->person_id,
                null,
                $subject->organization_id,
                $subject->person?->email ?? $subject->email,
                $subject->person?->display_name ?? $subject->person?->full_name ?? $subject->full_name,
            ));
        }

        if ($subject instanceof TrialRegistration) {
            $recipients->push($this->recipient(
                $subject->person_id ? 'person' : 'trial_registration',
                $subject->person_id ?? $subject->id,
                $subject->person_id ?? $subject->lead?->person_id,
                $subject->student_profile_id,
                $subject->lead?->organization_id,
                $subject->person?->email ?? $subject->lead?->person?->email ?? $subject->lead?->email,
                $subject->person?->display_name ?? $subject->person?->full_name ?? $subject->lead?->full_name,
            ));
        }

        if ($subject instanceof Order) {
            $account = $subject->customerAccount;
            $recipients->push($this->recipient(
                $account?->organization_id ? 'organization' : 'customer_account',
                $account?->organization_id ?? $account?->person_id ?? $account?->id,
                $account?->person_id,
                null,
                $account?->organization_id,
                $account?->email ?? $account?->person?->email ?? $account?->organization?->email ?? $subject->lead?->email,
                $account?->display_name ?? $account?->person?->display_name ?? $account?->organization?->name,
            ));
        }

        if ($subject instanceof Payment) {
            $account = $subject->customerAccount;
            $recipients->push($this->recipient(
                $account?->organization_id ? 'organization' : 'customer_account',
                $account?->organization_id ?? $account?->person_id ?? $account?->id,
                $account?->person_id,
                null,
                $account?->organization_id,
                $account?->email ?? $account?->person?->email ?? $account?->organization?->email,
                $account?->display_name ?? $account?->person?->display_name ?? $account?->organization?->name,
            ));
        }

        if ($subject instanceof Enrollment) {
            $recipients = $recipients->merge($this->studentRecipients($subject->studentProfile, $workflow->audience_type));
        }

        if ($subject instanceof ClassSession) {
            foreach ($subject->classGroup?->enrollments ?? collect() as $enrollment) {
                $recipients = $recipients->merge($this->studentRecipients($enrollment->studentProfile, $workflow->audience_type));
            }
        }

        if ($subject instanceof Invoice) {
            $account = $subject->customerAccount;
            $recipients->push($this->recipient(
                $account?->organization_id ? 'organization' : 'customer_account',
                $account?->organization_id ?? $account?->person_id ?? $account?->id,
                $account?->person_id,
                null,
                $account?->organization_id,
                $account?->email ?? $account?->person?->email ?? $account?->organization?->email,
                $account?->display_name ?? $account?->person?->display_name ?? $account?->organization?->name,
            ));
        }

        if ($subject instanceof ProgressReport) {
            $recipients = $recipients->merge($this->studentRecipients($subject->studentProfile, $workflow->audience_type, true));
        }

        return $recipients
            ->filter(fn (array $recipient): bool => filled($recipient['id']) || filled($recipient['email']))
            ->unique(fn (array $recipient): string => ($recipient['type'] ?? '-') . '|' . ($recipient['id'] ?? '-') . '|' . ($recipient['email'] ?? '-'))
            ->values();
    }

    private function studentRecipients($studentProfile, string $audienceType, bool $progressOnly = false): Collection
    {
        if (! $studentProfile) {
            return collect();
        }

        $recipients = collect();

        if (in_array($audienceType, ['student', 'learner'], true) || (! $progressOnly && $studentProfile->person?->email)) {
            $recipients->push($this->recipient(
                'student_profile',
                $studentProfile->id,
                $studentProfile->person_id,
                $studentProfile->id,
                $studentProfile->organization_id,
                $studentProfile->person?->email,
                $studentProfile->person?->display_name ?? $studentProfile->person?->full_name,
            ));
        }

        if (in_array($audienceType, ['guardian', 'parent', 'student', 'learner'], true)) {
            foreach ($studentProfile->guardians ?? collect() as $guardian) {
                if ($guardian->can_receive_notifications === false) {
                    continue;
                }

                if ($progressOnly && $guardian->can_view_progress === false) {
                    continue;
                }

                $recipients->push($this->recipient(
                    'guardian',
                    $guardian->guardian_person_id,
                    $guardian->guardian_person_id,
                    $studentProfile->id,
                    $studentProfile->organization_id,
                    $guardian->guardianPerson?->email,
                    $guardian->guardianPerson?->display_name ?? $guardian->guardianPerson?->full_name,
                ));
            }
        }

        return $recipients;
    }

    /**
     * @return array{type: string|null, id: string|null, person_id: string|null, student_profile_id: string|null, organization_id: string|null, email: string|null, name: string|null}
     */
    private function recipient(?string $type, mixed $id, mixed $personId, mixed $studentProfileId, mixed $organizationId, ?string $email, ?string $name): array
    {
        return [
            'type' => $type,
            'id' => $id ? (string) $id : null,
            'person_id' => $personId ? (string) $personId : null,
            'student_profile_id' => $studentProfileId ? (string) $studentProfileId : null,
            'organization_id' => $organizationId ? (string) $organizationId : null,
            'email' => $email,
            'name' => $name ?: 'bạn',
        ];
    }

    /**
     * @return array{type: string|null, id: string|null, person_id: string|null, student_profile_id: string|null, organization_id: string|null, email: string|null, name: string|null}
     */
    private function fallbackRecipient(): array
    {
        return $this->recipient(null, null, null, null, null, null, null);
    }

    /**
     * @param array<string, mixed> $recipient
     * @return array<string, mixed>
     */
    private function dedupeKeys(AutomationWorkflow $workflow, Model $subject, array $recipient): array
    {
        return [
            'automation_workflow_id' => $workflow->id,
            'subject_type' => $subject::class,
            'subject_id' => (string) $subject->getKey(),
            'recipient_type' => $recipient['type'],
            'recipient_id' => $recipient['id'] ?? $recipient['email'],
        ];
    }

    /**
     * @param array<string, mixed> $recipient
     * @param array<string, mixed> $extraPayload
     */
    private function writeAutomationLog(
        AutomationWorkflow $workflow,
        AutomationMessage $message,
        Model $subject,
        array $recipient,
        string $status,
        ?Notification $notification = null,
        ?string $errorMessage = null,
        array $extraPayload = [],
    ): void {
        AutomationLog::query()->updateOrCreate($this->dedupeKeys($workflow, $subject, $recipient), [
            'tenant_id' => $workflow->tenant_id,
            'automation_message_id' => $message->id,
            'notification_id' => $notification?->id,
            'trigger_type' => $workflow->trigger_type,
            'audience_type' => $workflow->audience_type,
            'channel' => $message->channel,
            'status' => $status,
            'error_message' => $errorMessage,
            'payload' => array_merge([
                'title' => $notification?->title,
                'body' => $notification?->body,
                'recipient_email' => $recipient['email'] ?? null,
                'recipient_name' => $recipient['name'] ?? null,
            ], $extraPayload),
            'scheduled_at' => now(),
            'sent_at' => $status === 'sent' ? now() : null,
        ]);
    }

    /**
     * @param array<string, mixed> $recipient
     */
    private function renderTemplate(string $template, Model $subject, array $recipient = []): string
    {
        $values = array_merge([
            'recipient_name' => $recipient['name'] ?? 'bạn',
        ], $this->templateValuesFor($subject));

        foreach ($values as $key => $value) {
            $template = str_replace('{{' . $key . '}}', (string) ($value ?? ''), $template);
        }

        return $template;
    }

    /**
     * @return array<string, mixed>
     */
    private function templateValuesFor(Model $subject): array
    {
        return match (true) {
            $subject instanceof Lead => [
                'name' => $subject->full_name,
                'phone' => $subject->phone,
                'course' => $subject->course_slug ?? $subject->interested_course_id,
                'follow_up_at' => $subject->next_follow_up_at?->format('d/m/Y H:i'),
            ],
            $subject instanceof TrialRegistration => [
                'name' => $subject->person?->display_name ?? $subject->person?->full_name ?? $subject->lead?->full_name,
                'course' => $subject->course_id,
                'preferred_date' => $subject->preferred_date?->format('d/m/Y'),
                'preferred_time' => $subject->preferred_time,
            ],
            $subject instanceof Order => [
                'name' => $subject->customerAccount?->display_name,
                'order_code' => $subject->order_code,
                'amount' => $this->formatMoney($subject->total_vnd),
                'balance' => $this->formatMoney($subject->balance_vnd),
                'course' => $subject->items->pluck('course.name')->filter()->join(', '),
            ],
            $subject instanceof Payment => [
                'name' => $subject->customerAccount?->display_name,
                'payment_code' => $subject->payment_code,
                'invoice_code' => $subject->invoice?->invoice_code,
                'amount' => $this->formatMoney($subject->amount_vnd),
                'paid_at' => $subject->paid_at?->format('d/m/Y H:i'),
            ],
            $subject instanceof Enrollment => [
                'name' => $subject->studentProfile?->person?->display_name ?? $subject->studentProfile?->person?->full_name,
                'course' => $subject->course?->name,
                'class' => $subject->classGroup?->name,
                'enrollment_code' => $subject->enrollment_code,
                'enrolled_at' => $subject->enrolled_at?->format('d/m/Y'),
            ],
            $subject instanceof ClassSession => [
                'name' => $subject->classGroup?->name,
                'course' => $subject->classGroup?->course?->name,
                'class' => $subject->classGroup?->name,
                'session' => $subject->title,
                'starts_at' => $subject->starts_at?->format('d/m/Y H:i'),
                'location' => $subject->location ?? $subject->classGroup?->location,
            ],
            $subject instanceof Invoice => [
                'name' => $subject->customerAccount?->display_name,
                'invoice_code' => $subject->invoice_code,
                'amount' => $this->formatMoney($subject->balance_vnd),
                'due_date' => $subject->due_date?->format('d/m/Y'),
            ],
            $subject instanceof ProgressReport => [
                'name' => $subject->studentProfile?->person?->display_name ?? $subject->studentProfile?->person?->full_name,
                'course' => $subject->course?->name,
                'class' => $subject->classGroup?->name,
                'report_title' => $subject->title,
                'period' => $subject->report_period,
                'progress_percent' => $subject->progress_percent,
                'overall_level' => $subject->overall_level,
                'strengths' => $subject->strengths,
                'improvements' => $subject->improvements,
                'recommendation' => $subject->recommendation,
            ],
            default => [],
        };
    }

    private function notificationTypeFor(string $triggerType): string
    {
        return match ($triggerType) {
            'class_session.upcoming' => 'schedule',
            'invoice.due_soon', 'payment.completed', 'order.created' => 'finance',
            'progress_report.published' => 'progress',
            'trial_registration.created', 'enrollment.created' => 'learning',
            default => 'general',
        };
    }

    private function formatMoney(mixed $amount): string
    {
        return number_format((int) $amount, 0, ',', '.') . 'đ';
    }

    public static function seedDefaultWorkflows(Tenant $tenant): void
    {
        $defaults = [
            [
                'code' => 'lead-new-confirmation-email',
                'name' => 'Xác nhận đăng ký tư vấn',
                'trigger_type' => 'lead.created',
                'audience_type' => 'lead',
                'channel' => 'email',
                'cooldown_hours' => 48,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'DAYAI đã nhận thông tin của {{name}}',
                    'body_template' => "Cảm ơn {{recipient_name}} đã quan tâm tới chương trình {{course}}.\n\nĐội ngũ DAYAI sẽ liên hệ để tư vấn lộ trình học AI phù hợp trong thời gian sớm nhất.",
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'trial-registration-confirmation-email',
                'name' => 'Xác nhận đăng ký học thử',
                'trigger_type' => 'trial_registration.created',
                'audience_type' => 'lead',
                'channel' => 'email',
                'cooldown_hours' => 24,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'DAYAI xác nhận lịch học thử của {{name}}',
                    'body_template' => "DAYAI đã nhận yêu cầu học thử của {{recipient_name}}.\n\nThời gian mong muốn: {{preferred_date}} {{preferred_time}}.\nBộ phận tuyển sinh sẽ xác nhận lịch chính thức trước buổi học.",
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'order-created-email',
                'name' => 'Xác nhận mua khóa học',
                'trigger_type' => 'order.created',
                'audience_type' => 'payer',
                'channel' => 'email',
                'cooldown_hours' => 24,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'DAYAI xác nhận đơn đăng ký {{order_code}}',
                    'body_template' => "DAYAI đã ghi nhận đơn đăng ký {{order_code}} cho {{course}}.\n\nTổng giá trị: {{amount}}.\nCòn cần thanh toán: {{balance}}.\n\nNếu cần hỗ trợ xuất hóa đơn hoặc lịch học, vui lòng phản hồi email này.",
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'payment-completed-email',
                'name' => 'Xác nhận thanh toán học phí',
                'trigger_type' => 'payment.completed',
                'audience_type' => 'payer',
                'channel' => 'email',
                'cooldown_hours' => 24,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'DAYAI đã nhận thanh toán {{payment_code}}',
                    'body_template' => "DAYAI xác nhận đã nhận thanh toán {{amount}} vào {{paid_at}}.\n\nMã thanh toán: {{payment_code}}.\nHóa đơn liên quan: {{invoice_code}}.\n\nCảm ơn {{recipient_name}} đã đồng hành cùng DAYAI.",
                    'priority' => 'high',
                ],
            ],
            [
                'code' => 'enrollment-welcome-email',
                'name' => 'Chào mừng học viên vào khóa học',
                'trigger_type' => 'enrollment.created',
                'audience_type' => 'student',
                'channel' => 'email',
                'cooldown_hours' => 72,
                'conditions' => ['within_hours' => 48],
                'message' => [
                    'title_template' => 'Chào mừng {{name}} đến với khóa {{course}}',
                    'body_template' => "DAYAI chào mừng {{name}} bắt đầu hành trình học AI.\n\nKhóa học: {{course}}.\nLớp: {{class}}.\nMã ghi danh: {{enrollment_code}}.\n\nHãy chuẩn bị tinh thần học thực chiến và ứng dụng AI an toàn, hiệu quả.",
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'class-session-reminder-email',
                'name' => 'Nhắc lịch học sắp tới',
                'trigger_type' => 'class_session.upcoming',
                'audience_type' => 'student',
                'channel' => 'email',
                'cooldown_hours' => 20,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'Nhắc lịch học: {{session}}',
                    'body_template' => "Buổi học {{session}} của lớp {{class}} sẽ bắt đầu lúc {{starts_at}}.\n\nĐịa điểm/hình thức: {{location}}.\nKhóa học: {{course}}.\n\nDAYAI chúc bạn có một buổi học hiệu quả.",
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'invoice-due-soon-email',
                'name' => 'Nhắc thanh toán học phí/công nợ',
                'trigger_type' => 'invoice.due_soon',
                'audience_type' => 'payer',
                'channel' => 'email',
                'cooldown_hours' => 24,
                'conditions' => ['within_days' => 3],
                'message' => [
                    'title_template' => 'Nhắc thanh toán {{invoice_code}}',
                    'body_template' => "Hóa đơn {{invoice_code}} còn số dư {{amount}} và đến hạn vào {{due_date}}.\n\nVui lòng hoàn tất thanh toán để DAYAI duy trì quyền học và hỗ trợ lớp học liên tục.",
                    'priority' => 'high',
                ],
            ],
            [
                'code' => 'progress-report-published-email',
                'name' => 'Gửi báo cáo tiến độ học định kỳ',
                'trigger_type' => 'progress_report.published',
                'audience_type' => 'guardian',
                'channel' => 'email',
                'cooldown_hours' => 120,
                'conditions' => ['within_days' => 7],
                'message' => [
                    'title_template' => 'Báo cáo tiến độ học: {{report_title}}',
                    'body_template' => "DAYAI gửi báo cáo tiến độ định kỳ của {{name}}.\n\nTiến độ: {{progress_percent}}%.\nMức độ tổng quan: {{overall_level}}.\n\nĐiểm mạnh:\n{{strengths}}\n\nCần cải thiện:\n{{improvements}}\n\nGợi ý tiếp theo:\n{{recommendation}}",
                    'priority' => 'high',
                ],
            ],
            [
                'code' => 'lead-follow-up-overdue-portal',
                'name' => 'Nhắc follow-up lead quá hạn cho nhân sự',
                'trigger_type' => 'lead.follow_up_overdue',
                'audience_type' => 'staff',
                'channel' => 'portal',
                'cooldown_hours' => 12,
                'conditions' => ['limit' => 50],
                'message' => [
                    'title_template' => 'Lead {{name}} đã quá hạn follow-up',
                    'body_template' => 'Lead cần được liên hệ lại. Lịch follow-up: {{follow_up_at}}.',
                    'priority' => 'high',
                ],
            ],
        ];

        foreach ($defaults as $default) {
            $workflow = AutomationWorkflow::query()->updateOrCreate(
                ['tenant_id' => $tenant->id, 'code' => $default['code']],
                [
                    'name' => $default['name'],
                    'trigger_type' => $default['trigger_type'],
                    'audience_type' => $default['audience_type'],
                    'channel' => $default['channel'],
                    'status' => 'active',
                    'cooldown_hours' => $default['cooldown_hours'],
                    'conditions' => $default['conditions'],
                ],
            );

            AutomationMessage::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'automation_workflow_id' => $workflow->id,
                    'channel' => $default['channel'],
                ],
                [
                    'message_type' => 'notification',
                    'title_template' => $default['message']['title_template'],
                    'body_template' => $default['message']['body_template'],
                    'priority' => $default['message']['priority'],
                    'status' => 'active',
                ],
            );
        }
    }
}
