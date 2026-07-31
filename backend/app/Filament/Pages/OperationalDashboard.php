<?php

namespace App\Filament\Pages;

use App\Models\AttendanceRecord;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePartner;
use App\Models\AutomationLog;
use App\Models\AutomationWorkflow;
use App\Services\AutomationWorkflowRunner;
use Filament\Notifications\Notification;
use App\Models\ClassGroup;
use App\Models\ClassSession;
use App\Models\ContentItem;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProgressReport;
use App\Models\Receivable;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class OperationalDashboard extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Tổng quan';

    protected static ?string $navigationLabel = 'Dashboard vận hành';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.operational-dashboard';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('admin') || $user?->can('view_dashboard') || false;
    }

    public function getTitle(): string
    {
        return 'Dashboard tuyển sinh & vận hành';
    }

    /**
     * @return array<string, int>
     */
    public function getSummary(): array
    {
        $leadCount = Lead::query()->count();
        $convertedLeadCount = Lead::query()->whereNotNull('converted_at')->count();

        return [
            'lead_count' => $leadCount,
            'new_lead_count' => Lead::query()->where('status', 'new')->count(),
            'converted_lead_count' => $convertedLeadCount,
            'conversion_rate' => $leadCount > 0 ? (int) round($convertedLeadCount * 100 / $leadCount) : 0,
            'active_enrollment_count' => Enrollment::query()->where('status', 'active')->count(),
            'active_class_count' => ClassGroup::query()->whereIn('status', ['enrolling', 'active', 'ongoing'])->count(),
            'upcoming_session_count' => ClassSession::query()->where('starts_at', '>=', now())->count(),
            'at_risk_attendance_count' => AttendanceRecord::query()->whereIn('status', ['absent', 'late', 'excused'])->count(),
            'order_total_vnd' => (int) Order::query()->sum('total_vnd'),
            'paid_vnd' => (int) Payment::query()->where('status', 'completed')->sum('amount_vnd'),
            'receivable_vnd' => (int) Receivable::query()->sum('balance_vnd'),
            'average_progress_percent' => (int) round(ProgressReport::query()
                ->where('status', 'published')
                ->avg('progress_percent') ?? 0),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function getLeadFunnel(): Collection
    {
        return Lead::query()
            ->select('status')
            ->selectRaw('count(*) as total')
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getLeadSources(): Collection
    {
        return DB::table('leads')
            ->leftJoin('lead_sources', 'lead_sources.id', '=', 'leads.lead_source_id')
            ->selectRaw("coalesce(lead_sources.name, 'Chưa rõ') as source_name")
            ->selectRaw('count(leads.id) as lead_count')
            ->selectRaw('sum(case when leads.converted_at is not null then 1 else 0 end) as converted_count')
            ->groupBy('source_name')
            ->orderByDesc('lead_count')
            ->limit(8)
            ->get();
    }

    /**
     * @return array<string, int>
     */
    public function getPhase2Readiness(): array
    {
        return [
            'published_course_count' => Course::query()->where('status', 'published')->count(),
            'published_content_count' => ContentItem::query()->where('status', 'published')->count(),
            'published_video_count' => VideoLesson::query()->where('status', 'published')->count(),
            'tracked_lesson_progress_count' => VideoLessonProgress::query()->count(),
            'lead_source_count' => Lead::query()->whereNotNull('lead_source_id')->count(),
            'affiliate_lead_count' => Lead::query()->whereNotNull('affiliate_code')->count(),
            'affiliate_partner_count' => AffiliatePartner::query()->where('status', 'active')->count(),
            'affiliate_click_count' => AffiliateClick::query()->count(),
            'pending_commission_vnd' => (int) AffiliateCommission::query()->where('status', 'pending')->sum('commission_vnd'),
            'active_student_count' => Enrollment::query()->where('status', 'active')->distinct('student_profile_id')->count('student_profile_id'),
            'published_progress_report_count' => ProgressReport::query()->where('status', 'published')->count(),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function getAffiliatePerformance(): Collection
    {
        return DB::table('affiliate_commissions')
            ->leftJoin('affiliate_partners', 'affiliate_partners.id', '=', 'affiliate_commissions.affiliate_partner_id')
            ->selectRaw("coalesce(affiliate_partners.name, affiliate_commissions.affiliate_code, 'Chưa rõ') as partner_name")
            ->selectRaw('count(affiliate_commissions.id) as commission_count')
            ->selectRaw('sum(affiliate_commissions.order_total_vnd) as order_total_vnd')
            ->selectRaw('sum(affiliate_commissions.commission_vnd) as commission_vnd')
            ->selectRaw("sum(case when affiliate_commissions.status = 'pending' then affiliate_commissions.commission_vnd else 0 end) as pending_commission_vnd")
            ->groupBy('partner_name')
            ->orderByDesc('commission_vnd')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getAutomationHealth(): array
    {
        return [
            'active_workflow_count' => AutomationWorkflow::query()->where('status', 'active')->count(),
            'sent_log_count' => AutomationLog::query()->where('status', 'sent')->count(),
            'failed_log_count' => AutomationLog::query()->where('status', 'failed')->count(),
            'skipped_log_count' => AutomationLog::query()->where('status', 'skipped')->count(),
            'latest_sent_at' => AutomationLog::query()->where('status', 'sent')->max('sent_at'),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function getAutomationRecentLogs(): Collection
    {
        return AutomationLog::query()
            ->with('workflow:id,name')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    /**
     * @return Collection<int, object>
     */
    public function getLeadTemperatures(): Collection
    {
        return Lead::query()
            ->select('temperature')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(expected_value_vnd) as expected_value_vnd')
            ->groupBy('temperature')
            ->orderByDesc('total')
            ->get();
    }

    /**
     * @return Collection<int, Lead>
     */
    public function getOverdueLeads(): Collection
    {
        return Lead::query()
            ->with(['source:id,name', 'assignedUser:id,name'])
            ->whereNotNull('next_follow_up_at')
            ->where('next_follow_up_at', '<', now())
            ->whereNotIn('status', ['registered', 'lost', 'not_fit', 'duplicate'])
            ->orderBy('next_follow_up_at')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getSalesPerformance(): Collection
    {
        return DB::table('leads')
            ->leftJoin('users', 'users.id', '=', 'leads.assigned_user_id')
            ->selectRaw("coalesce(users.name, 'Chưa phân công') as consultant_name")
            ->selectRaw('count(leads.id) as lead_count')
            ->selectRaw('sum(case when leads.converted_at is not null then 1 else 0 end) as converted_count')
            ->selectRaw('sum(leads.expected_value_vnd) as expected_value_vnd')
            ->groupBy('consultant_name')
            ->orderByDesc('lead_count')
            ->limit(8)
            ->get();
    }
    public function getUpcomingSessions(): Collection
    {
        return ClassSession::query()
            ->with(['classGroup.course', 'teacherProfile.person'])
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getAtRiskStudents(): Collection
    {
        return DB::table('attendance_records')
            ->join('student_profiles', 'student_profiles.id', '=', 'attendance_records.student_profile_id')
            ->join('people', 'people.id', '=', 'student_profiles.person_id')
            ->leftJoin('class_sessions', 'class_sessions.id', '=', 'attendance_records.class_session_id')
            ->leftJoin('class_groups', 'class_groups.id', '=', 'class_sessions.class_group_id')
            ->select('student_profiles.student_code', 'people.full_name', 'class_groups.name as class_group_name')
            ->selectRaw("sum(case when attendance_records.status = 'absent' then 1 else 0 end) as absent_count")
            ->selectRaw("sum(case when attendance_records.status = 'late' then 1 else 0 end) as late_count")
            ->whereIn('attendance_records.status', ['absent', 'late', 'excused'])
            ->groupBy('student_profiles.student_code', 'people.full_name', 'class_groups.name')
            ->orderByDesc('absent_count')
            ->orderByDesc('late_count')
            ->limit(8)
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    public function getCourseRevenue(): Collection
    {
        return DB::table('order_items')
            ->join('courses', 'courses.id', '=', 'order_items.course_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('courses.name as course_name')
            ->selectRaw('sum(order_items.line_total_vnd) as sold_vnd')
            ->selectRaw('sum(case when orders.total_vnd > 0 then floor(orders.paid_vnd * order_items.line_total_vnd / orders.total_vnd) else 0 end) as paid_vnd')
            ->groupBy('courses.name')
            ->orderByDesc('sold_vnd')
            ->limit(8)
            ->get();
    }

    public function formatVnd(int|string|null $amount): string
    {
        return number_format((int) $amount, 0, ',', '.') . ' ₫';
    }

    public function formatStatus(?string $status): string
    {
        return [
            'new' => 'Mới',
            'contacting' => 'Đang liên hệ',
            'consulting' => 'Đang tư vấn',
            'trial_scheduled' => 'Hẹn học thử',
            'registered' => 'Đã đăng ký',
            'lost' => 'Mất',
            'active' => 'Đang học',
            'enrolling' => 'Đang tuyển sinh',
            'scheduled' => 'Đã lên lịch',
        ][$status ?? ''] ?? ($status ?: 'Chưa rõ');
    }
    public function formatTemperature(?string $temperature): string
    {
        return [
            'hot' => 'Nóng',
            'warm' => 'Ấm',
            'cold' => 'Lạnh',
        ][$temperature ?? ''] ?? 'Chưa rõ';
    }

    public function runAutomation(): void
    {
        try {
            $runner = app(AutomationWorkflowRunner::class);
            $summary = $runner->run();

            Notification::make()
                ->title('Chạy automation thành công')
                ->success()
                ->body(sprintf(
                    'Đã quét xong: %d workflow, %d lead/học viên. Gửi thành công: %d, Bỏ qua: %d, Thất bại: %d.',
                    $summary['workflow_count'],
                    $summary['processed_count'],
                    $summary['sent_count'],
                    $summary['skipped_count'],
                    $summary['failed_count']
                ))
                ->send();
        } catch (\Throwable $exception) {
            Notification::make()
                ->title('Lỗi chạy automation')
                ->danger()
                ->body($exception->getMessage())
                ->send();
        }
    }
}
