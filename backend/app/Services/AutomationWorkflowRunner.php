<?php

namespace App\Services;

use App\Models\AutomationLog;
use App\Models\AutomationMessage;
use App\Models\AutomationWorkflow;
use App\Models\ClassSession;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
                    $result = $this->sendMessage($workflow, $message, $subject);
                    $summary[$result . '_count']++;
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
                ->where('tenant_id', $workflow->tenant_id)
                ->whereIn('status', ['new', 'trial_requested'])
                ->where('created_at', '>=', now()->subHours($workflow->conditions['within_hours'] ?? 24))
                ->latest()
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'lead.follow_up_overdue' => Lead::query()
                ->where('tenant_id', $workflow->tenant_id)
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<', now())
                ->whereNotIn('status', ['registered', 'lost', 'not_fit', 'duplicate'])
                ->orderBy('next_follow_up_at')
                ->limit($workflow->conditions['limit'] ?? 50)
                ->get(),
            'class_session.upcoming' => ClassSession::query()
                ->with(['classGroup.course', 'classGroup.enrollments.studentProfile.person'])
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
            default => collect(),
        };
    }

    private function sendMessage(AutomationWorkflow $workflow, AutomationMessage $message, Model $subject): string
    {
        $recipient = $this->resolveRecipient($workflow, $subject);
        $dedupe = [
            'automation_workflow_id' => $workflow->id,
            'subject_type' => $subject::class,
            'subject_id' => (string) $subject->getKey(),
            'recipient_type' => $recipient['type'],
            'recipient_id' => $recipient['id'],
        ];

        $existingLog = AutomationLog::query()->where($dedupe)->first();

        if ($existingLog && $existingLog->created_at?->greaterThan(now()->subHours($workflow->cooldown_hours))) {
            return 'skipped';
        }

        try {
            $notification = Notification::query()->create([
                'tenant_id' => $workflow->tenant_id,
                'person_id' => $recipient['person_id'],
                'student_profile_id' => $recipient['student_profile_id'],
                'organization_id' => $recipient['organization_id'],
                'audience_type' => $workflow->audience_type,
                'notification_type' => $this->notificationTypeFor($workflow->trigger_type),
                'channel' => $message->channel,
                'title' => $this->renderTemplate($message->title_template, $subject),
                'body' => $this->renderTemplate($message->body_template, $subject),
                'status' => 'published',
                'priority' => $message->priority,
                'published_at' => now(),
                'metadata' => [
                    'automation_workflow_id' => $workflow->id,
                    'automation_message_id' => $message->id,
                    'subject_type' => $subject::class,
                    'subject_id' => $subject->getKey(),
                ],
            ]);

            AutomationLog::query()->updateOrCreate($dedupe, [
                'tenant_id' => $workflow->tenant_id,
                'automation_message_id' => $message->id,
                'notification_id' => $notification->id,
                'trigger_type' => $workflow->trigger_type,
                'audience_type' => $workflow->audience_type,
                'channel' => $message->channel,
                'status' => 'sent',
                'error_message' => null,
                'payload' => [
                    'title' => $notification->title,
                    'body' => $notification->body,
                ],
                'scheduled_at' => now(),
                'sent_at' => now(),
            ]);

            return 'sent';
        } catch (Throwable $exception) {
            AutomationLog::query()->updateOrCreate($dedupe, [
                'tenant_id' => $workflow->tenant_id,
                'automation_message_id' => $message->id,
                'trigger_type' => $workflow->trigger_type,
                'audience_type' => $workflow->audience_type,
                'channel' => $message->channel,
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'scheduled_at' => now(),
            ]);

            return 'failed';
        }
    }

    /**
     * @return array{type: string|null, id: string|null, person_id: string|null, student_profile_id: string|null, organization_id: string|null}
     */
    private function resolveRecipient(AutomationWorkflow $workflow, Model $subject): array
    {
        if ($subject instanceof Lead) {
            return [
                'type' => $subject->person_id ? 'person' : 'lead',
                'id' => $subject->person_id ?? $subject->id,
                'person_id' => $subject->person_id,
                'student_profile_id' => null,
                'organization_id' => $subject->organization_id,
            ];
        }

        if ($subject instanceof ClassSession) {
            $student = $subject->classGroup?->enrollments?->first()?->studentProfile;

            return [
                'type' => $student ? 'student_profile' : 'class_session',
                'id' => $student?->id ?? $subject->id,
                'person_id' => $student?->person_id,
                'student_profile_id' => $student?->id,
                'organization_id' => null,
            ];
        }

        if ($subject instanceof Invoice) {
            return [
                'type' => $subject->customerAccount?->organization_id ? 'organization' : 'person',
                'id' => $subject->customerAccount?->organization_id ?? $subject->customerAccount?->person_id,
                'person_id' => $subject->customerAccount?->person_id,
                'student_profile_id' => null,
                'organization_id' => $subject->customerAccount?->organization_id,
            ];
        }

        return [
            'type' => null,
            'id' => null,
            'person_id' => null,
            'student_profile_id' => null,
            'organization_id' => null,
        ];
    }

    private function renderTemplate(string $template, Model $subject): string
    {
        $values = match (true) {
            $subject instanceof Lead => [
                'name' => $subject->full_name,
                'phone' => $subject->phone,
                'course' => $subject->course_slug ?? $subject->interested_course_id,
                'follow_up_at' => $subject->next_follow_up_at?->format('d/m/Y H:i'),
            ],
            $subject instanceof ClassSession => [
                'name' => $subject->classGroup?->name,
                'course' => $subject->classGroup?->course?->name,
                'session' => $subject->title,
                'starts_at' => $subject->starts_at?->format('d/m/Y H:i'),
            ],
            $subject instanceof Invoice => [
                'name' => $subject->customerAccount?->display_name,
                'invoice_code' => $subject->invoice_code,
                'amount' => number_format($subject->balance_vnd, 0, ',', '.') . 'đ',
                'due_date' => $subject->due_date?->format('d/m/Y'),
            ],
            default => [],
        };

        foreach ($values as $key => $value) {
            $template = str_replace('{{' . $key . '}}', (string) ($value ?? ''), $template);
        }

        return $template;
    }

    private function notificationTypeFor(string $triggerType): string
    {
        return match ($triggerType) {
            'class_session.upcoming' => 'schedule',
            'invoice.due_soon' => 'finance',
            default => 'general',
        };
    }

    public static function seedDefaultWorkflows(Tenant $tenant): void
    {
        $defaults = [
            [
                'code' => 'lead-new-confirmation',
                'name' => 'Xác nhận lead mới',
                'trigger_type' => 'lead.created',
                'audience_type' => 'lead',
                'cooldown_hours' => 48,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'DAYAI đã nhận thông tin của {{name}}',
                    'body_template' => 'Cảm ơn bạn đã quan tâm khóa {{course}}. Tư vấn viên DAYAI sẽ liên hệ trong thời gian sớm nhất.',
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'lead-follow-up-overdue',
                'name' => 'Nhắc follow-up lead quá hạn',
                'trigger_type' => 'lead.follow_up_overdue',
                'audience_type' => 'staff',
                'cooldown_hours' => 12,
                'conditions' => ['limit' => 50],
                'message' => [
                    'title_template' => 'Lead {{name}} đã quá hạn follow-up',
                    'body_template' => 'Lead cần được liên hệ lại. Lịch follow-up: {{follow_up_at}}.',
                    'priority' => 'high',
                ],
            ],
            [
                'code' => 'class-session-reminder',
                'name' => 'Nhắc lịch học sắp tới',
                'trigger_type' => 'class_session.upcoming',
                'audience_type' => 'student',
                'cooldown_hours' => 20,
                'conditions' => ['within_hours' => 24],
                'message' => [
                    'title_template' => 'Lịch học {{session}} sắp diễn ra',
                    'body_template' => 'Buổi học {{session}} của lớp {{name}} bắt đầu lúc {{starts_at}}.',
                    'priority' => 'normal',
                ],
            ],
            [
                'code' => 'invoice-due-soon',
                'name' => 'Nhắc học phí/công nợ sắp đến hạn',
                'trigger_type' => 'invoice.due_soon',
                'audience_type' => 'payer',
                'cooldown_hours' => 24,
                'conditions' => ['within_days' => 3],
                'message' => [
                    'title_template' => 'Nhắc thanh toán {{invoice_code}}',
                    'body_template' => 'Số tiền còn lại {{amount}} sẽ đến hạn vào {{due_date}}.',
                    'priority' => 'high',
                ],
            ],
        ];

        foreach ($defaults as $default) {
            $workflow = AutomationWorkflow::query()->firstOrCreate(
                ['tenant_id' => $tenant->id, 'code' => $default['code']],
                [
                    'name' => $default['name'],
                    'trigger_type' => $default['trigger_type'],
                    'audience_type' => $default['audience_type'],
                    'channel' => 'portal',
                    'status' => 'active',
                    'cooldown_hours' => $default['cooldown_hours'],
                    'conditions' => $default['conditions'],
                ],
            );

            AutomationMessage::query()->firstOrCreate(
                ['tenant_id' => $tenant->id, 'automation_workflow_id' => $workflow->id],
                [
                    'message_type' => 'notification',
                    'channel' => 'portal',
                    'title_template' => $default['message']['title_template'],
                    'body_template' => $default['message']['body_template'],
                    'priority' => $default['message']['priority'],
                    'status' => 'active',
                ],
            );
        }
    }
}
