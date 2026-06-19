<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\Branch;
use App\Models\Certificate;
use App\Models\ClassGroup;
use App\Models\Course;
use App\Models\CustomerAccount;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\Person;
use App\Models\ProgressReport;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyPortalLookupApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_portal_lookup_returns_company_students_progress_and_finance(): void
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

        $company = Organization::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'name' => 'Example Corp',
            'short_name' => 'EXAMPLE-CORP',
            'tax_code' => 'EXAMPLE-CORP',
            'organization_type' => 'company',
            'status' => 'active',
        ]);

        $hrPerson = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'HR Example',
            'email' => 'hr@examplecorp.test',
        ]);

        OrganizationContact::query()->create([
            'tenant_id' => $tenant->id,
            'organization_id' => $company->id,
            'person_id' => $hrPerson->id,
            'contact_role' => 'hr',
            'is_primary' => true,
            'status' => 'active',
        ]);

        $teacherPerson = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Giáo viên B2B',
        ]);

        $teacher = TeacherProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $teacherPerson->id,
            'teacher_code' => 'GV-B2B',
            'status' => 'active',
        ]);

        $course = Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Cho Doanh Nghiệp',
            'slug' => 'ai-cho-doanh-nghiep',
            'course_code' => 'AI-B2B',
            'default_price_vnd' => 4000000,
            'status' => 'published',
        ]);

        $classGroup = ClassGroup::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'course_id' => $course->id,
            'teacher_profile_id' => $teacher->id,
            'class_code' => 'B2B-PORTAL',
            'name' => 'B2B Portal Demo',
            'status' => 'enrolling',
        ]);

        $studentPerson = Person::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'full_name' => 'Nhân sự Demo',
            'email' => 'staff@examplecorp.test',
        ]);

        $student = StudentProfile::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $studentPerson->id,
            'organization_id' => $company->id,
            'student_code' => 'HV-B2B-TEST',
            'student_type' => 'company_staff',
            'job_title' => 'Sales Executive',
            'status' => 'active',
        ]);

        $customer = CustomerAccount::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'organization_id' => $company->id,
            'customer_code' => 'KH-B2B-TEST',
            'account_type' => 'company',
            'display_name' => 'Example Corp',
            'status' => 'active',
        ]);

        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'customer_account_id' => $customer->id,
            'order_code' => 'ORD-B2B-TEST',
            'order_type' => 'b2b',
            'status' => 'partially_paid',
            'ordered_at' => now(),
            'total_vnd' => 6000000,
            'paid_vnd' => 3000000,
            'balance_vnd' => 3000000,
        ]);

        $enrollment = Enrollment::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'order_id' => $order->id,
            'enrollment_code' => 'ENR-B2B-TEST',
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $assessment = Assessment::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'title' => 'Đánh giá B2B',
            'assessment_type' => 'entry',
            'status' => 'published',
        ]);

        AssessmentResult::query()->create([
            'tenant_id' => $tenant->id,
            'assessment_id' => $assessment->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'teacher_profile_id' => $teacher->id,
            'score' => 8,
            'max_score' => 10,
            'level' => 'on_track',
            'status' => 'published',
            'assessed_at' => now(),
        ]);

        ProgressReport::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'teacher_profile_id' => $teacher->id,
            'title' => 'Báo cáo B2B tuần 1',
            'status' => 'published',
            'overall_level' => 'on_track',
            'progress_percent' => 45,
            'published_at' => now(),
        ]);

        Notification::query()->create([
            'tenant_id' => $tenant->id,
            'person_id' => $hrPerson->id,
            'organization_id' => $company->id,
            'audience_type' => 'company',
            'notification_type' => 'progress',
            'channel' => 'portal',
            'title' => 'Báo cáo nhóm đã sẵn sàng',
            'body' => 'HR có thể xem tiến độ nhóm trong portal.',
            'status' => 'published',
            'priority' => 'normal',
            'published_at' => now(),
        ]);

        Certificate::query()->create([
            'tenant_id' => $tenant->id,
            'student_profile_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'course_id' => $course->id,
            'class_group_id' => $classGroup->id,
            'certificate_code' => 'CERT-B2B-TEST',
            'verification_token' => 'verify-b2b-test',
            'title' => 'Chứng chỉ B2B',
            'status' => 'issued',
            'final_score' => 8,
            'grade' => 'Khá',
            'issued_at' => now(),
        ]);

        $response = $this->postJson('/api/company-portal/lookup', [
            'email' => 'hr@examplecorp.test',
            'company_code' => 'EXAMPLE-CORP',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('organization.name', 'Example Corp')
            ->assertJsonPath('summary.student_count', 1)
            ->assertJsonPath('summary.balance_vnd', 3000000)
            ->assertJsonPath('students.0.student_code', 'HV-B2B-TEST')
            ->assertJsonPath('students.0.latest_progress.progress_percent', 45)
            ->assertJsonPath('students.0.latest_assessment.score', 8)
            ->assertJsonPath('students.0.latest_certificate.certificate_code', 'CERT-B2B-TEST')
            ->assertJsonPath('notifications.0.title', 'Báo cáo nhóm đã sẵn sàng');
    }

    public function test_company_portal_lookup_returns_not_found_for_wrong_hr(): void
    {
        $response = $this->postJson('/api/company-portal/lookup', [
            'email' => 'wrong@examplecorp.test',
            'company_code' => 'EXAMPLE-CORP',
        ]);

        $response->assertNotFound();
    }
}
