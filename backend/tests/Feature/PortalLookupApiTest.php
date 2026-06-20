<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Branch;
use App\Models\Certificate;
use App\Models\ClassGroup;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CustomerAccount;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Person;
use App\Models\ProgressReport;
use App\Models\StudentProfile;
use App\Models\TeacherComment;
use App\Models\TeacherProfile;
use App\Models\Tenant;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalLookupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_portal_lookup_returns_student_learning_finance_and_video_data(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Cơ sở chính',
            'code' => 'main',
            'status' => 'active',
        ]);

        $person = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Học viên Portal',
            'phone' => '0901888000',
        ]);

        $teacherPerson = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Giáo viên Portal',
            'phone' => '0901888111',
        ]);

        $teacherProfile = TeacherProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $teacherPerson->id,
            'teacher_code' => 'GV-PORTAL',
            'status' => 'active',
        ]);

        $student = StudentProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'student_code' => 'HV-PORTAL',
            'student_type' => 'university_student',
            'status' => 'active',
        ]);

        $course = Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Căn Bản',
            'slug' => 'ai-can-ban',
            'course_code' => 'AI-FUNDAMENTALS',
            'default_price_vnd' => 3500000,
            'status' => 'published',
        ]);

        $module = CourseModule::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'sort_order' => 1,
            'title' => 'Nền tảng AI',
            'description' => 'Hiểu AI và ứng dụng đúng cách.',
            'duration_minutes' => 90,
            'learning_objectives' => ['Hiểu cách dùng AI an toàn'],
        ]);

        $classGroup = ClassGroup::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'course_id' => $course->id,
            'class_code' => 'AI-PORTAL',
            'name' => 'AI Portal Demo',
            'status' => 'enrolling',
        ]);

        ClassSession::query()->create([
            'tenant_id' => $tenant->id,
            'class_group_id' => $classGroup->id,
            'session_no' => 1,
            'title' => 'Buổi 1',
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addDay()->addHours(2),
            'status' => 'scheduled',
        ]);

        $customer = CustomerAccount::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'customer_code' => 'KH-PORTAL',
            'account_type' => 'individual',
            'display_name' => 'Học viên Portal',
            'phone' => '0901888000',
            'status' => 'active',
        ]);

        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'customer_account_id' => $customer->id,
            'order_code' => 'ORD-PORTAL',
            'order_type' => 'b2c',
            'status' => 'partially_paid',
            'ordered_at' => now(),
            'total_vnd' => 3000000,
            'paid_vnd' => 1500000,
            'balance_vnd' => 1500000,
        ]);

        $enrollment = Enrollment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'order_id' => $order->id,
            'enrollment_code' => 'ENR-PORTAL',
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'title' => 'Đánh giá đầu vào',
            'assessment_type' => 'entry',
            'status' => 'published',
            'max_score' => 10,
            'assessment_at' => now(),
        ]);

        AssessmentResult::query()->create([
            'tenant_id' => $tenant->id,
            'assessment_id' => $assessment->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'teacher_profile_id' => $teacherProfile->id,
            'score' => 8,
            'max_score' => 10,
            'level' => 'on_track',
            'status' => 'published',
            'feedback' => 'Tư duy tốt, cần luyện prompt.',
            'assessed_at' => now(),
        ]);

        TeacherComment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'teacher_profile_id' => $teacherProfile->id,
            'comment_type' => 'progress',
            'visibility' => 'guardian',
            'title' => 'Nhận xét tuần 1',
            'comment' => 'Học viên chủ động trong buổi học.',
            'rating' => 4,
            'commented_at' => now(),
        ]);

        ProgressReport::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'teacher_profile_id' => $teacherProfile->id,
            'report_period' => 'weekly',
            'title' => 'Báo cáo tuần 1',
            'status' => 'published',
            'overall_level' => 'on_track',
            'progress_percent' => 35,
            'strengths' => 'Nắm nhanh ví dụ thực tế.',
            'improvements' => 'Cần luyện thêm prompt.',
            'recommendation' => 'Hoàn thành worksheet trước buổi sau.',
            'published_at' => now(),
        ]);

        Notification::query()->create([
            'tenant_id' => $tenant->id,
            'person_id' => $person->id,
            'student_profile_id' => $student->id,
            'audience_type' => 'student',
            'notification_type' => 'schedule',
            'channel' => 'portal',
            'title' => 'Nhắc lịch học',
            'body' => 'Buổi học tiếp theo bắt đầu lúc 19:30.',
            'status' => 'published',
            'priority' => 'high',
            'published_at' => now(),
        ]);

        Certificate::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'certificate_code' => 'CERT-PORTAL',
            'verification_token' => 'verify-portal',
            'title' => 'Chứng chỉ Portal',
            'status' => 'issued',
            'final_score' => 8.5,
            'grade' => 'Giỏi',
            'issued_at' => now(),
        ]);

        $videoLesson = VideoLesson::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'course_module_id' => $module->id,
            'sort_order' => 1,
            'title' => 'Video Portal',
            'slug' => 'video-portal',
            'status' => 'published',
            'video_provider' => 'youtube',
            'video_url' => 'https://example.com/video-portal',
            'access_level' => 'student',
            'duration_minutes' => 12,
            'resources' => [
                ['title' => 'Worksheet AI', 'url' => 'https://example.com/worksheet.pdf'],
            ],
            'published_at' => now(),
        ]);

        VideoLessonProgress::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'video_lesson_id' => $videoLesson->id,
            'status' => 'completed',
            'progress_percent' => 100,
            'last_position_seconds' => 720,
            'started_at' => now()->subHour(),
            'completed_at' => now(),
            'last_watched_at' => now(),
        ]);

        $response = $this->postJson('/api/portal/lookup', [
            'phone' => '0901888000',
            'student_code' => 'HV-PORTAL',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('student.full_name', 'Học viên Portal')
            ->assertJsonPath('summary.active_enrollments', 1)
            ->assertJsonPath('summary.latest_progress_percent', 35)
            ->assertJsonPath('summary.finance_balance_vnd', 1500000)
            ->assertJsonPath('enrollments.0.course', 'AI Căn Bản')
            ->assertJsonPath('assessment_results.0.assessment', 'Đánh giá đầu vào')
            ->assertJsonPath('teacher_comments.0.title', 'Nhận xét tuần 1')
            ->assertJsonPath('progress_reports.0.progress_percent', 35)
            ->assertJsonPath('notifications.0.title', 'Nhắc lịch học')
            ->assertJsonPath('certificates.0.certificate_code', 'CERT-PORTAL')
            ->assertJsonPath('finance.balance_vnd', 1500000)
            ->assertJsonPath('lms.overall_progress_percent', 100)
            ->assertJsonPath('lms.courses.0.course', 'AI Căn Bản')
            ->assertJsonPath('lms.courses.0.modules.0.title', 'Nền tảng AI')
            ->assertJsonPath('lms.courses.0.modules.0.lessons.0.title', 'Video Portal')
            ->assertJsonPath('lms.courses.0.modules.0.lessons.0.progress.status', 'completed')
            ->assertJsonPath('lms.courses.0.modules.0.lessons.0.progress.progress_percent', 100)
            ->assertJsonPath('videos.0.title', 'Video Portal')
            ->assertJsonPath('videos.0.progress_percent', 100);
    }

    public function test_portal_lookup_returns_not_found_for_wrong_credentials(): void
    {
        $response = $this->postJson('/api/portal/lookup', [
            'phone' => '000',
            'student_code' => 'NOPE',
        ]);

        $response->assertNotFound();
    }
}
