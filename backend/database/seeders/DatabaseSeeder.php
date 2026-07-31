<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\QuestionOption;
use App\Models\QuestionBank;
use App\Models\Question;
use App\Models\AssessmentQuestion;
use App\Models\AttendanceRecord;
use App\Models\Branch;
use App\Models\Certificate;
use App\Models\ClassGroup;
use App\Models\ClassSession;
use App\Models\ConsultationActivity;
use App\Models\ContentCategory;
use App\Models\ContentItem;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CustomerAccount;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadAssignment;
use App\Models\LeadSource;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Organization;
use App\Models\OrganizationContact;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Receivable;
use App\Models\Receipt;
use App\Models\ProgressReport;
use App\Models\StudentProfile;
use App\Models\TeacherComment;
use App\Models\TeacherProfile;
use App\Models\Tenant;
use App\Models\TrialRegistration;
use App\Models\User;
use App\Models\VideoLesson;
use App\Models\VideoLessonProgress;
use App\Services\AutomationWorkflowRunner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tenant = Tenant::query()->firstOrCreate(
            ['code' => 'dayai'],
            [
                'name' => 'DAYAI',
                'status' => 'active',
                'settings' => [
                    'timezone' => 'Asia/Bangkok',
                    'currency' => 'VND',
                ],
            ],
        );

        AutomationWorkflowRunner::seedDefaultWorkflows($tenant);

        $branch = Branch::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'code' => 'main',
            ],
            [
                'name' => 'Cơ sở chính',
                'status' => 'active',
            ],
        );

        $person = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => env('DAYAI_ADMIN_EMAIL', 'admin@dayai.edu.vn'),
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'DAYAI Admin',
                'display_name' => 'Admin',
                'phone' => env('DAYAI_ADMIN_PHONE'),
            ],
        );

        $user = User::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => env('DAYAI_ADMIN_EMAIL', 'admin@dayai.edu.vn'),
            ],
            [
                'person_id' => $person->id,
                'name' => 'DAYAI Admin',
                'phone' => env('DAYAI_ADMIN_PHONE'),
                'password' => Hash::make(env('DAYAI_ADMIN_PASSWORD', 'password')),
                'status' => 'active',
            ],
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $rolePermissions = [
            'admin' => [
                'view_dashboard',
                'manage_crm',
                'manage_learning',
                'manage_finance',
                'manage_progress',
                'manage_content',
                'manage_admin',
            ],
            'sales' => [
                'view_dashboard',
                'manage_crm',
                'manage_content',
            ],
            'teacher' => [
                'view_dashboard',
                'manage_learning',
                'manage_progress',
            ],
            'accountant' => [
                'view_dashboard',
                'manage_finance',
            ],
        ];

        foreach (collect($rolePermissions)->flatten()->unique() as $permissionName) {
            Permission::query()->firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web',
            ]);
        }

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::query()->firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($permissions);
        }

        $user->assignRole('admin');

        $demoUsers = [
            [
                'role' => 'sales',
                'name' => 'DAYAI Sales Demo',
                'email' => 'sales@dayai.edu.vn',
                'phone' => '0901888001',
            ],
            [
                'role' => 'teacher',
                'name' => 'DAYAI Teacher Demo',
                'email' => 'teacher@dayai.edu.vn',
                'phone' => '0901888002',
            ],
            [
                'role' => 'accountant',
                'name' => 'DAYAI Accountant Demo',
                'email' => 'accountant@dayai.edu.vn',
                'phone' => '0901888003',
            ],
        ];

        foreach ($demoUsers as $demoUser) {
            $demoPerson = Person::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'email' => $demoUser['email'],
                ],
                [
                    'branch_id' => $branch->id,
                    'full_name' => $demoUser['name'],
                    'display_name' => str_replace('DAYAI ', '', $demoUser['name']),
                    'phone' => $demoUser['phone'],
                ],
            );

            $demoAccount = User::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'email' => $demoUser['email'],
                ],
                [
                    'person_id' => $demoPerson->id,
                    'name' => $demoUser['name'],
                    'phone' => $demoUser['phone'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                ],
            );

            $demoAccount->syncRoles([$demoUser['role']]);
        }

        ActivityLog::query()->firstOrCreate([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'action' => 'system.seeded',
            'subject_type' => Tenant::class,
            'subject_id' => $tenant->id,
        ], [
            'description' => 'Initial DAYAI tenant, branch, admin user, and roles seeded.',
        ]);

        $sources = collect([
            ['name' => 'Website', 'code' => 'website', 'source_type' => 'website'],
            ['name' => 'Facebook', 'code' => 'facebook', 'source_type' => 'social'],
            ['name' => 'Zalo', 'code' => 'zalo', 'source_type' => 'social'],
            ['name' => 'Giới thiệu', 'code' => 'referral', 'source_type' => 'referral'],
            ['name' => 'Affiliate / Đối tác giới thiệu', 'code' => 'affiliate', 'source_type' => 'affiliate'],
            ['name' => 'Google Ads', 'code' => 'google_ads', 'source_type' => 'paid_media'],
            ['name' => 'TikTok', 'code' => 'tiktok', 'source_type' => 'paid_media'],
            ['name' => 'YouTube', 'code' => 'youtube', 'source_type' => 'paid_media'],
            ['name' => 'SEO Organic', 'code' => 'organic_search', 'source_type' => 'organic'],
            ['name' => 'Chatbot', 'code' => 'chatbot', 'source_type' => 'chatbot'],
            ['name' => 'Sự kiện / Workshop', 'code' => 'event', 'source_type' => 'event'],
            ['name' => 'Nhập thủ công', 'code' => 'manual', 'source_type' => 'manual'],
        ])->mapWithKeys(fn (array $source) => [
            $source['code'] => LeadSource::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'code' => $source['code'],
                ],
                [
                    'name' => $source['name'],
                    'source_type' => $source['source_type'],
                    'is_active' => true,
                ],
            ),
        ]);

        $sampleLeads = [
            [
                'lead_type' => 'parent',
                'status' => 'new',
                'priority' => 'high',
                'full_name' => 'Nguyễn Minh Anh',
                'phone' => '0901000001',
                'email' => 'minhanh.parent@example.com',
                'learning_goal' => 'Muốn tìm khóa AI cho con lớp 7.',
                'message' => 'Cần tư vấn lộ trình học thử cuối tuần.',
                'source' => 'website',
                'next_follow_up_at' => now()->addDay(),
            ],
            [
                'lead_type' => 'student',
                'status' => 'contacting',
                'priority' => 'normal',
                'full_name' => 'Trần Quốc Bảo',
                'phone' => '0901000002',
                'email' => 'baostudent@example.com',
                'learning_goal' => 'Muốn học AI để làm đồ án đại học.',
                'message' => 'Quan tâm khóa AI ứng dụng cho sinh viên.',
                'source' => 'facebook',
                'last_contacted_at' => now()->subHours(5),
                'next_follow_up_at' => now()->addDays(2),
            ],
            [
                'lead_type' => 'business_owner',
                'status' => 'consulting',
                'priority' => 'urgent',
                'full_name' => 'Lê Hoàng Nam',
                'phone' => '0901000003',
                'email' => 'nam.ceo@example.com',
                'company_name' => 'Nam Digital',
                'learning_goal' => 'Ứng dụng AI vào marketing và vận hành.',
                'message' => 'Chủ doanh nghiệp muốn học lớp tối.',
                'source' => 'zalo',
                'last_contacted_at' => now()->subDay(),
                'next_follow_up_at' => now()->addHours(6),
            ],
            [
                'lead_type' => 'company',
                'status' => 'trial_scheduled',
                'priority' => 'high',
                'full_name' => 'Phạm Thu Hà',
                'phone' => '0901000004',
                'email' => 'hr@examplecorp.test',
                'company_name' => 'Example Corp',
                'learning_goal' => 'Đào tạo AI cho đội sales và marketing.',
                'message' => 'Công ty muốn mua khóa cho 20 nhân sự.',
                'source' => 'referral',
                'last_contacted_at' => now()->subDays(2),
                'next_follow_up_at' => now()->addDays(1),
            ],
        ];

        foreach ($sampleLeads as $sampleLead) {
            $lead = Lead::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'phone' => $sampleLead['phone'],
                ],
                [
                    'branch_id' => $branch->id,
                    'lead_source_id' => $sources[$sampleLead['source']]->id,
                    'assigned_user_id' => $user->id,
                    'lead_type' => $sampleLead['lead_type'],
                    'status' => $sampleLead['status'],
                    'priority' => $sampleLead['priority'],
                    'full_name' => $sampleLead['full_name'],
                    'email' => $sampleLead['email'],
                    'company_name' => $sampleLead['company_name'] ?? null,
                    'learning_goal' => $sampleLead['learning_goal'],
                    'message' => $sampleLead['message'],
                    'preferred_contact_method' => 'phone',
                    'last_contacted_at' => $sampleLead['last_contacted_at'] ?? null,
                    'next_follow_up_at' => $sampleLead['next_follow_up_at'] ?? null,
                    'created_by_id' => $user->id,
                    'updated_by_id' => $user->id,
                ],
            );

            LeadAssignment::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'lead_id' => $lead->id,
                    'assigned_to_user_id' => $user->id,
                ],
                [
                    'assigned_by_user_id' => $user->id,
                    'assigned_at' => now(),
                    'note' => 'Dữ liệu demo Sprint 2',
                ],
            );
        }

        $companyLead = Lead::query()->where('tenant_id', $tenant->id)->where('lead_type', 'company')->first();

        if ($companyLead) {
            ConsultationActivity::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'lead_id' => $companyLead->id,
                    'activity_type' => 'call',
                    'subject' => 'Gọi HR xác nhận nhu cầu đào tạo',
                ],
                [
                    'organization_id' => $companyLead->organization_id,
                    'direction' => 'outbound',
                    'content' => 'HR quan tâm lớp AI ứng dụng cho team sales/marketing, cần đề xuất lịch học thử.',
                    'outcome' => 'trial_booked',
                    'activity_at' => now()->subDay(),
                    'next_follow_up_at' => now()->addDay(),
                    'created_by_id' => $user->id,
                ],
            );

            TrialRegistration::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'lead_id' => $companyLead->id,
                ],
                [
                    'person_id' => $companyLead->person_id,
                    'preferred_date' => now()->addDays(3)->toDateString(),
                    'preferred_time' => '19:30 - 20:30',
                    'status' => 'scheduled',
                    'note' => 'Demo học thử cho nhóm công ty.',
                    'created_by_id' => $user->id,
                ],
            );
        }

        $teacherPerson = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => 'teacher@dayai.edu.vn',
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'Giảng viên DAYAI',
                'display_name' => 'Mentor AI',
                'phone' => '0901999000',
            ],
        );

        $teacherProfile = TeacherProfile::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'person_id' => $teacherPerson->id,
            ],
            [
                'branch_id' => $branch->id,
                'teacher_code' => 'GV-000001',
                'title' => 'AI Mentor',
                'bio' => 'Giảng viên phụ trách các lớp AI ứng dụng.',
                'specialties' => ['AI căn bản', 'Prompt Engineering', 'Tự động hóa'],
                'status' => 'active',
            ],
        );

        $course = Course::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'course_code' => 'AI-FUNDAMENTALS',
            ],
            [
                'name' => 'AI Căn Bản Cho Người Mới',
                'slug' => 'ai-can-ban-cho-nguoi-moi',
                'audience_type' => 'mixed',
                'level' => 'beginner',
                'short_description' => 'Khóa nhập môn giúp học viên hiểu và ứng dụng AI vào học tập/công việc.',
                'description' => 'Học viên làm quen với AI, prompt, công cụ phổ biến và một số bài thực hành ứng dụng.',
                'outcomes' => ['Hiểu nền tảng AI', 'Viết prompt hiệu quả', 'Ứng dụng AI vào công việc hằng ngày'],
                'duration_hours' => 12,
                'default_session_count' => 6,
                'default_price_vnd' => 3500000,
                'status' => 'published',
            ],
        );

        $knowledgeCategory = ContentCategory::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'slug' => 'kien-thuc-ai',
            ],
            [
                'name' => 'Kho kiến thức AI',
                'category_type' => 'knowledge',
                'description' => 'Bài viết, checklist, prompt mẫu và case study AI.',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        $videoCategory = ContentCategory::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'slug' => 'video-academy',
            ],
            [
                'name' => 'Video Academy',
                'category_type' => 'video',
                'description' => 'Video bài học, workshop replay và học thử.',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        foreach ([
            [
                'title' => 'AI là gì và người mới nên bắt đầu từ đâu?',
                'slug' => 'ai-la-gi-nguoi-moi-bat-dau-tu-dau',
                'content_type' => 'article',
                'excerpt' => 'Bài nhập môn giúp phụ huynh, sinh viên và người đi làm hiểu cách bắt đầu học AI.',
            ],
            [
                'title' => 'Checklist ứng dụng AI cho học tập và công việc',
                'slug' => 'checklist-ung-dung-ai-hoc-tap-cong-viec',
                'content_type' => 'checklist',
                'excerpt' => 'Danh sách việc cần chuẩn bị trước khi dùng AI vào học tập, báo cáo và tự động hóa.',
            ],
            [
                'title' => '10 prompt mẫu cho người mới học AI',
                'slug' => '10-prompt-mau-cho-nguoi-moi-hoc-ai',
                'content_type' => 'prompt_library',
                'excerpt' => 'Bộ prompt mẫu để người học thực hành ngay sau buổi đầu tiên.',
            ],
        ] as $contentItem) {
            ContentItem::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $contentItem['slug'],
                ],
                [
                    'content_category_id' => $knowledgeCategory->id,
                    'course_id' => $course->id,
                    'author_id' => $user->id,
                    'title' => $contentItem['title'],
                    'content_type' => $contentItem['content_type'],
                    'status' => 'published',
                    'excerpt' => $contentItem['excerpt'],
                    'body' => 'Nội dung demo Sprint 7. Khi triển khai thật, đội nội dung sẽ thay bằng bài viết đầy đủ.',
                    'tags' => ['ai', 'dayai', 'mvp'],
                    'published_at' => now(),
                ],
            );
        }

        foreach ([
            [
                'title' => 'Học thử: AI căn bản trong 20 phút',
                'slug' => 'hoc-thu-ai-can-ban-20-phut',
                'duration_minutes' => 20,
                'access_level' => 'public',
            ],
            [
                'title' => 'Workshop replay: Prompt Engineering cho người mới',
                'slug' => 'workshop-replay-prompt-engineering-nguoi-moi',
                'duration_minutes' => 45,
                'access_level' => 'lead_magnet',
            ],
            [
                'title' => 'Bài tập thực hành: tạo trợ lý học tập bằng AI',
                'slug' => 'bai-tap-tao-tro-ly-hoc-tap-bang-ai',
                'duration_minutes' => 30,
                'access_level' => 'student',
            ],
        ] as $videoLesson) {
            VideoLesson::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $videoLesson['slug'],
                ],
                [
                    'course_id' => $course->id,
                    'title' => $videoLesson['title'],
                    'status' => 'published',
                    'video_provider' => 'youtube',
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'duration_minutes' => $videoLesson['duration_minutes'],
                    'access_level' => $videoLesson['access_level'],
                    'summary' => 'Video demo cho module Video Academy của DAYAI.',
                    'resources' => ['worksheet' => 'Demo worksheet'],
                    'published_at' => now(),
                    'metadata' => ['category_id' => $videoCategory->id],
                ],
            );
        }

        foreach ([
            ['sort_order' => 1, 'title' => 'Tổng quan AI và ứng dụng thực tế', 'duration_minutes' => 120],
            ['sort_order' => 2, 'title' => 'Prompt Engineering căn bản', 'duration_minutes' => 120],
            ['sort_order' => 3, 'title' => 'Thực hành AI cho học tập và công việc', 'duration_minutes' => 120],
        ] as $module) {
            CourseModule::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'course_id' => $course->id,
                    'sort_order' => $module['sort_order'],
                ],
                [
                    'title' => $module['title'],
                    'duration_minutes' => $module['duration_minutes'],
                ],
            );
        }

        $classGroup = ClassGroup::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'class_code' => 'AI-0626-01',
            ],
            [
                'branch_id' => $branch->id,
                'course_id' => $course->id,
                'teacher_profile_id' => $teacherProfile->id,
                'name' => 'AI Căn Bản - Lớp tối T2/T4',
                'learning_format' => 'hybrid',
                'start_date' => now()->addWeek()->toDateString(),
                'end_date' => now()->addWeeks(4)->toDateString(),
                'max_students' => 20,
                'status' => 'enrolling',
                'schedule_note' => 'Tối thứ 2/4, 19:30 - 21:30',
                'location' => 'DAYAI Main / Google Meet',
            ],
        );

        $modules = $course->modules()->take(2)->get()->values();

        foreach ([1, 2] as $sessionNo) {
            ClassSession::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'class_group_id' => $classGroup->id,
                    'session_no' => $sessionNo,
                ],
                [
                    'course_module_id' => $modules->get($sessionNo - 1)?->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'title' => $modules->get($sessionNo - 1)?->title ?? "Buổi {$sessionNo}",
                    'starts_at' => now()->addWeek()->addDays(($sessionNo - 1) * 2)->setTime(19, 30),
                    'ends_at' => now()->addWeek()->addDays(($sessionNo - 1) * 2)->setTime(21, 30),
                    'status' => 'scheduled',
                    'location' => $classGroup->location,
                ],
            );
        }

        $studentPerson = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => 'student.demo@dayai.edu.vn',
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'Học viên Demo',
                'display_name' => 'Demo Student',
                'phone' => '0901888000',
            ],
        );

        $studentProfile = StudentProfile::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'person_id' => $studentPerson->id,
            ],
            [
                'branch_id' => $branch->id,
                'student_code' => 'HV-000001',
                'student_type' => 'university_student',
                'learning_goal' => 'Học AI để áp dụng vào học tập và đồ án.',
                'entry_level' => 'beginner',
                'status' => 'active',
            ],
        );

        $enrollment = Enrollment::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'student_profile_id' => $studentProfile->id,
                'course_id' => $course->id,
                'class_group_id' => $classGroup->id,
            ],
            [
                'enrollment_code' => 'ENR-000001',
                'status' => 'active',
                'enrolled_at' => now(),
                'started_at' => now()->addWeek(),
                'notes' => 'Dữ liệu demo Sprint 3',
            ],
        );

        $course->forceFill([
            'name' => 'AI Căn Bản Cho Người Mới',
            'short_description' => 'Khóa nhập môn giúp học viên hiểu và ứng dụng AI vào học tập, công việc hằng ngày.',
            'description' => 'Học viên làm quen với nền tảng AI, cách viết prompt, quy trình kiểm chứng kết quả và thực hành tạo sản phẩm nhỏ bằng AI.',
            'outcomes' => ['Hiểu nền tảng AI', 'Viết prompt hiệu quả', 'Ứng dụng AI vào học tập và công việc'],
        ])->save();

        $classGroup->forceFill([
            'name' => 'AI Căn Bản - Lớp tối T2/T4',
            'schedule_note' => 'Tối thứ 2/4, 19:30 - 21:30',
            'status' => 'active',
        ])->save();

        $studentPerson->forceFill([
            'full_name' => 'Học viên Demo',
            'display_name' => 'Học viên Demo',
        ])->save();

        $studentProfile->forceFill([
            'learning_goal' => 'Học AI để áp dụng vào học tập, làm bài tập, thuyết trình và đồ án cá nhân.',
            'status' => 'active',
        ])->save();

        foreach ([
            ['sort_order' => 1, 'title' => 'Tổng quan AI và cách học an toàn', 'description' => 'Hiểu AI là gì, dùng AI có trách nhiệm và biết cách kiểm chứng thông tin.', 'duration_minutes' => 90, 'learning_objectives' => ['Hiểu khái niệm AI', 'Biết giới hạn của AI', 'Dùng AI an toàn']],
            ['sort_order' => 2, 'title' => 'Prompt Engineering căn bản', 'description' => 'Thực hành viết prompt rõ mục tiêu, có ngữ cảnh và tiêu chí đầu ra.', 'duration_minutes' => 120, 'learning_objectives' => ['Viết prompt có cấu trúc', 'Tối ưu câu hỏi', 'Đánh giá kết quả AI']],
            ['sort_order' => 3, 'title' => 'Dự án: Trợ lý học tập bằng AI', 'description' => 'Tạo trợ lý học tập cá nhân để tóm tắt, lập kế hoạch và luyện tập.', 'duration_minutes' => 120, 'learning_objectives' => ['Thiết kế workflow học tập', 'Tạo prompt mẫu', 'Trình bày sản phẩm cuối khóa']],
        ] as $demoModule) {
            CourseModule::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'course_id' => $course->id,
                    'sort_order' => $demoModule['sort_order'],
                ],
                [
                    'title' => $demoModule['title'],
                    'description' => $demoModule['description'],
                    'duration_minutes' => $demoModule['duration_minutes'],
                    'learning_objectives' => $demoModule['learning_objectives'],
                ],
            );
        }

        $modules = $course->modules()->orderBy('sort_order')->get()->values();

        $interactiveAiIntro = [
            'required_for_completion' => true,
            'checkpoints' => [
                [
                    'id' => 'ai-definition',
                    'at_seconds' => 30,
                    'time_limit_seconds' => 30,
                    'question' => 'Trí tuệ nhân tạo (AI) được hiểu đúng nhất là gì?',
                    'options' => [
                        ['id' => 'robot', 'label' => 'Chỉ là những robot có hình dáng giống con người'],
                        ['id' => 'capability', 'label' => 'Khả năng máy thực hiện các nhiệm vụ thường cần trí thông minh con người'],
                        ['id' => 'internet', 'label' => 'Một tên gọi khác của Internet'],
                    ],
                    'correct_option_id' => 'capability',
                    'explanation' => 'AI là nhóm công nghệ giúp máy nhận biết, suy luận, tạo nội dung hoặc ra quyết định trong những nhiệm vụ vốn cần trí thông minh con người.',
                ],
                [
                    'id' => 'ai-learning',
                    'at_seconds' => 60,
                    'time_limit_seconds' => 30,
                    'question' => 'AI tạo sinh học cách tạo câu trả lời chủ yếu từ đâu?',
                    'options' => [
                        ['id' => 'patterns', 'label' => 'Các mẫu và mối quan hệ trong dữ liệu huấn luyện'],
                        ['id' => 'feelings', 'label' => 'Cảm xúc và trải nghiệm cá nhân của máy'],
                        ['id' => 'guess', 'label' => 'Đoán ngẫu nhiên hoàn toàn'],
                    ],
                    'correct_option_id' => 'patterns',
                    'explanation' => 'Mô hình học các mẫu thống kê trong dữ liệu và dự đoán đầu ra phù hợp, không có cảm xúc hay hiểu biết như con người.',
                ],
                [
                    'id' => 'human-role',
                    'at_seconds' => 90,
                    'time_limit_seconds' => 30,
                    'question' => 'Vai trò quan trọng nhất của người học khi dùng AI là gì?',
                    'options' => [
                        ['id' => 'copy', 'label' => 'Sao chép nguyên câu trả lời để tiết kiệm thời gian'],
                        ['id' => 'verify', 'label' => 'Đặt yêu cầu rõ, kiểm chứng và chịu trách nhiệm về kết quả'],
                        ['id' => 'delegate', 'label' => 'Giao toàn bộ quyết định cho AI'],
                    ],
                    'correct_option_id' => 'verify',
                    'explanation' => 'AI là trợ lý. Người dùng vẫn phải xác định mục tiêu, kiểm chứng thông tin và chịu trách nhiệm với sản phẩm cuối cùng.',
                ],
                [
                    'id' => 'safe-data',
                    'at_seconds' => 120,
                    'time_limit_seconds' => 30,
                    'question' => 'Thông tin nào không nên đưa vào công cụ AI công cộng?',
                    'options' => [
                        ['id' => 'public', 'label' => 'Một câu hỏi kiến thức phổ thông'],
                        ['id' => 'private', 'label' => 'Mật khẩu, dữ liệu cá nhân hoặc tài liệu mật'],
                        ['id' => 'practice', 'label' => 'Một đoạn văn tự viết để nhờ góp ý'],
                    ],
                    'correct_option_id' => 'private',
                    'explanation' => 'Không nhập mật khẩu, dữ liệu định danh, hồ sơ khách hàng hay nội dung mật vào công cụ AI công cộng.',
                ],
            ],
            'timeline_notes' => [
                ['at_seconds' => 0, 'title' => 'Bắt đầu từ vấn đề', 'text' => 'AI nên được nhìn như một nhóm công cụ hỗ trợ con người xử lý nhiệm vụ, không chỉ là robot hay chatbot.'],
                ['at_seconds' => 20, 'title' => 'Hiểu đúng về AI', 'text' => 'Máy học từ các mẫu trong dữ liệu để nhận biết, dự đoán hoặc tạo ra nội dung phù hợp với yêu cầu.'],
                ['at_seconds' => 40, 'title' => 'AI tạo sinh hoạt động thế nào?', 'text' => 'Mô hình tạo sinh dự đoán đầu ra có khả năng phù hợp; câu trả lời nghe tự tin vẫn có thể chưa chính xác.'],
                ['at_seconds' => 60, 'title' => 'Bắt đầu bằng việc nhỏ', 'text' => 'Chọn một công việc lặp lại, mô tả rõ mục tiêu và thử nghiệm trước khi áp dụng rộng.'],
                ['at_seconds' => 80, 'title' => 'Con người giữ vai trò quyết định', 'text' => 'Người dùng đặt yêu cầu, kiểm chứng thông tin và chịu trách nhiệm với sản phẩm cuối cùng.'],
                ['at_seconds' => 100, 'title' => 'Bảo vệ dữ liệu', 'text' => 'Không đưa mật khẩu, dữ liệu định danh, hồ sơ khách hàng hoặc tài liệu mật vào công cụ AI công cộng.'],
                ['at_seconds' => 120, 'title' => 'Công thức học AI', 'text' => 'Học một khái niệm, thực hành bằng prompt, kiểm tra kết quả rồi ghi lại điều có thể áp dụng.'],
            ],
            'prompt_notes' => [
                [
                    'id' => 'explain-ai',
                    'title' => 'Giải thích AI theo trình độ của tôi',
                    'prompt' => 'Bạn là giảng viên AI. Hãy giải thích khái niệm trí tuệ nhân tạo cho một người mới bắt đầu bằng ngôn ngữ đơn giản, kèm 3 ví dụ gần gũi trong học tập hoặc công việc.',
                ],
                [
                    'id' => 'apply-ai',
                    'title' => 'Tìm cơ hội ứng dụng AI',
                    'prompt' => 'Tôi đang học/làm công việc: [mô tả]. Hãy đề xuất 5 việc AI có thể hỗ trợ, lợi ích dự kiến, rủi ro cần kiểm soát và bước thử nghiệm đầu tiên cho từng việc.',
                ],
                [
                    'id' => 'verify-ai',
                    'title' => 'Kiểm chứng câu trả lời của AI',
                    'prompt' => 'Hãy tự rà soát câu trả lời trên: liệt kê các khẳng định cần kiểm chứng, đề xuất nguồn đáng tin cậy để đối chiếu, chỉ ra điểm chưa chắc chắn và viết lại phần kết luận thận trọng hơn.',
                ],
            ],
        ];

        foreach ([
            ['module_index' => 0, 'sort_order' => 1, 'title' => 'Bài 1: AI là gì và học AI bắt đầu từ đâu?', 'slug' => 'ai-la-gi-va-hoc-ai-bat-dau-tu-dau', 'duration_minutes' => 3, 'summary' => 'Bài nhập môn giúp học viên hiểu đúng về AI, ứng dụng thực tế và rủi ro cần tránh.', 'progress' => 0, 'status' => 'not_started', 'video_provider' => 'internal', 'video_url' => '/course-videos/ai-la-gi-demo.mp4', 'interactive_learning' => $interactiveAiIntro],
            ['module_index' => 0, 'sort_order' => 2, 'title' => 'Bài 2: Nguyên tắc dùng AI an toàn', 'slug' => 'nguyen-tac-dung-ai-an-toan', 'duration_minutes' => 18, 'summary' => 'Cách bảo vệ dữ liệu cá nhân, kiểm chứng nguồn và tránh phụ thuộc vào AI.', 'progress' => 70, 'status' => 'in_progress'],
            ['module_index' => 1, 'sort_order' => 1, 'title' => 'Bài 3: Công thức prompt rõ mục tiêu', 'slug' => 'cong-thuc-prompt-ro-muc-tieu', 'duration_minutes' => 32, 'summary' => 'Thực hành khung prompt gồm vai trò, bối cảnh, nhiệm vụ, ràng buộc và tiêu chí đánh giá.', 'progress' => 35, 'status' => 'in_progress'],
            ['module_index' => 1, 'sort_order' => 2, 'title' => 'Bài 4: Dùng AI để học và làm bài thuyết trình', 'slug' => 'dung-ai-de-hoc-va-lam-bai-thuyet-trinh', 'duration_minutes' => 28, 'summary' => 'Ứng dụng AI để lập dàn ý, tạo slide, luyện phản biện và chuẩn bị trình bày.', 'progress' => 0, 'status' => 'not_started'],
            ['module_index' => 2, 'sort_order' => 1, 'title' => 'Bài 5: Xây trợ lý học tập cá nhân', 'slug' => 'xay-tro-ly-hoc-tap-ca-nhan', 'duration_minutes' => 40, 'summary' => 'Dự án cuối khóa: thiết kế trợ lý AI hỗ trợ ôn tập, tóm tắt và lập kế hoạch học.', 'progress' => 0, 'status' => 'not_started'],
        ] as $demoLesson) {
            $lesson = VideoLesson::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'slug' => $demoLesson['slug'],
                ],
                [
                    'course_id' => $course->id,
                    'course_module_id' => $modules->get($demoLesson['module_index'])?->id,
                    'sort_order' => $demoLesson['sort_order'],
                    'title' => $demoLesson['title'],
                    'status' => 'published',
                    'video_provider' => $demoLesson['video_provider'] ?? 'youtube',
                    'video_url' => $demoLesson['video_url'] ?? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'duration_minutes' => $demoLesson['duration_minutes'],
                    'access_level' => 'student',
                    'summary' => $demoLesson['summary'],
                    'resources' => [['title' => 'Worksheet thực hành', 'url' => '/tai-nguyen/mau-prompt-chatgpt/']],
                    'published_at' => now(),
                    'metadata' => array_filter([
                        'category_id' => $videoCategory->id,
                        'interactive_learning' => $demoLesson['interactive_learning'] ?? null,
                    ], fn ($value): bool => $value !== null),
                ],
            );

            VideoLessonProgress::query()->updateOrCreate(
                [
                    'student_profile_id' => $studentProfile->id,
                    'video_lesson_id' => $lesson->id,
                ],
                [
                    'tenant_id' => $tenant->id,
                    'enrollment_id' => $enrollment->id,
                    'status' => $demoLesson['status'],
                    'progress_percent' => $demoLesson['progress'],
                    'last_position_seconds' => $demoLesson['progress'] > 0 ? 420 : 0,
                    'started_at' => $demoLesson['progress'] > 0 ? now()->subDays(2) : null,
                    'last_watched_at' => $demoLesson['progress'] > 0 ? now()->subHours(6) : null,
                    'completed_at' => $demoLesson['status'] === 'completed' ? now()->subDay() : null,
                ],
            );
        }

        $firstSession = $classGroup->sessions()->orderBy('session_no')->first();

        if ($firstSession) {
            AttendanceRecord::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'class_session_id' => $firstSession->id,
                    'student_profile_id' => $studentProfile->id,
                ],
                [
                    'enrollment_id' => $enrollment->id,
                    'status' => 'present',
                    'checked_in_at' => $firstSession->starts_at,
                    'checked_by_id' => $user->id,
                    'teacher_note' => 'Điểm danh demo Sprint 3.',
                ],
            );
        }

        $entryAssessment = Assessment::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'class_group_id' => $classGroup->id,
                'assessment_type' => 'entry',
            ],
            [
                'course_id' => $course->id,
                'title' => 'Đánh giá đầu vào AI Căn Bản',
                'status' => 'published',
                'max_score' => 10,
                'weight_percent' => 20,
                'assessment_at' => now()->subDays(2),
                'description' => 'Kiểm tra tư duy logic, khả năng dùng công cụ AI và mục tiêu học tập.',
            ],
        );


        $firstModule = $modules->first();
        $firstLesson = VideoLesson::query()
            ->where('tenant_id', $tenant->id)
            ->where('course_id', $course->id)
            ->orderBy('sort_order')
            ->first();

        $entryAssessment->forceFill([
            'course_id' => $course->id,
            'course_module_id' => $firstModule?->id,
            'video_lesson_id' => $firstLesson?->id,
            'assessment_type' => 'entry',
            'status' => 'published',
            'max_score' => 10,
            'weight_percent' => 20,
            'assessment_at' => now()->subDays(2),
            'description' => 'Kiểm tra tư duy logic, khả năng dùng công cụ AI và mục tiêu học tập.',
        ])->save();

        $questionBank = QuestionBank::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'course_id' => $course->id,
                'bank_type' => 'course',
                'audience_type' => 'student',
                'level' => 'beginner',
            ],
            [
                'name' => 'Question Bank - AI Căn Bản',
                'course_module_id' => $firstModule?->id,
                'video_lesson_id' => $firstLesson?->id,
                'status' => 'active',
                'description' => 'Bộ câu hỏi demo cho kiểm tra đầu vào và quiz nhanh của khóa AI Căn Bản.',
                'tags' => ['ai-can-ban', 'quiz', 'demo'],
            ],
        );

        $demoQuestions = [
            [
                'sort_order' => 1,
                'prompt' => 'Khi dùng AI để hỗ trợ học tập, hành động nào an toàn và đúng nhất?',
                'question_type' => 'single_choice',
                'difficulty' => 'easy',
                'explanation' => 'AI nên được dùng như trợ lý học tập; người học vẫn cần kiểm chứng và hiểu bản chất.',
                'options' => [
                    ['content' => 'Sao chép nguyên văn câu trả lời của AI mà không kiểm tra', 'is_correct' => false],
                    ['content' => 'Dùng AI để gợi ý, sau đó tự kiểm chứng và diễn đạt lại', 'is_correct' => true],
                    ['content' => 'Đưa toàn bộ thông tin cá nhân lên AI để nhận câu trả lời chính xác hơn', 'is_correct' => false],
                    ['content' => 'Tin mọi nguồn AI đưa ra vì AI luôn đúng', 'is_correct' => false],
                ],
            ],
            [
                'sort_order' => 2,
                'prompt' => 'Một prompt tốt thường cần yếu tố nào?',
                'question_type' => 'multiple_choice',
                'difficulty' => 'medium',
                'explanation' => 'Prompt tốt nên có vai trò, bối cảnh, nhiệm vụ, ràng buộc và tiêu chí đầu ra rõ ràng.',
                'options' => [
                    ['content' => 'Mục tiêu rõ ràng', 'is_correct' => true],
                    ['content' => 'Bối cảnh đủ cụ thể', 'is_correct' => true],
                    ['content' => 'Yêu cầu càng mơ hồ càng tốt', 'is_correct' => false],
                    ['content' => 'Tiêu chí đánh giá kết quả', 'is_correct' => true],
                ],
            ],
            [
                'sort_order' => 3,
                'prompt' => 'Viết ngắn gọn 2 việc cần làm để kiểm chứng một câu trả lời do AI tạo ra.',
                'question_type' => 'short_answer',
                'difficulty' => 'medium',
                'explanation' => 'Có thể kiểm tra nguồn, so sánh nhiều nguồn, hỏi lại AI về căn cứ và nhờ giáo viên hoặc mentor phản biện.',
                'options' => [],
            ],
        ];

        foreach ($demoQuestions as $demoQuestion) {
            $question = Question::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'question_bank_id' => $questionBank->id,
                    'question_type' => $demoQuestion['question_type'],
                ],
                [
                    'prompt' => $demoQuestion['prompt'],
                    'course_id' => $course->id,
                    'course_module_id' => $firstModule?->id,
                    'video_lesson_id' => $firstLesson?->id,
                    'difficulty' => $demoQuestion['difficulty'],
                    'status' => 'published',
                    'explanation' => $demoQuestion['explanation'],
                    'default_score' => $demoQuestion['question_type'] === 'short_answer' ? 2 : 1,
                    'time_limit_seconds' => 120,
                    'tags' => ['ai-can-ban', 'entry'],
                ],
            );

            foreach ($demoQuestion['options'] as $optionIndex => $option) {
                QuestionOption::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'question_id' => $question->id,
                        'sort_order' => $optionIndex + 1,
                    ],
                    [
                        'content' => $option['content'],
                        'is_correct' => $option['is_correct'],
                    ],
                );
            }

            AssessmentQuestion::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'assessment_id' => $entryAssessment->id,
                    'question_id' => $question->id,
                ],
                [
                    'sort_order' => $demoQuestion['sort_order'],
                    'score' => $question->default_score,
                    'is_required' => true,
                ],
            );
        }

        $moduleQuiz = Assessment::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'course_id' => $course->id,
                'title' => 'Quiz nhanh: AI an toàn & prompt rõ mục tiêu',
            ],
            [
                'course_module_id' => $firstModule?->id,
                'video_lesson_id' => null,
                'class_group_id' => $classGroup->id,
                'assessment_type' => 'quiz',
                'status' => 'published',
                'max_score' => 4,
                'weight_percent' => 10,
                'assessment_at' => now(),
                'description' => 'Bài quiz ngắn sau module đầu tiên để kiểm tra mức hiểu về học AI an toàn và cách viết prompt rõ ràng.',
            ],
        );

        $duplicateEntryAssessmentIds = Assessment::query()
            ->where('tenant_id', $tenant->id)
            ->where('class_group_id', $classGroup->id)
            ->where('assessment_type', 'entry')
            ->where('id', '!=', $entryAssessment->id)
            ->pluck('id');

        if ($duplicateEntryAssessmentIds->isNotEmpty()) {
            Assessment::query()
                ->whereIn('id', $duplicateEntryAssessmentIds)
                ->update(['status' => 'archived']);

            AssessmentResult::query()
                ->where('student_profile_id', $studentProfile->id)
                ->whereIn('assessment_id', $duplicateEntryAssessmentIds)
                ->update(['status' => 'archived']);
        }

        Question::query()
            ->where('question_bank_id', $questionBank->id)
            ->orderBy('created_at')
            ->get()
            ->each(function (Question $question, int $index) use ($moduleQuiz, $tenant): void {
                AssessmentQuestion::query()->updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'assessment_id' => $moduleQuiz->id,
                        'question_id' => $question->id,
                    ],
                    [
                        'sort_order' => $index + 1,
                        'score' => $question->default_score,
                        'is_required' => true,
                    ],
                );
            });

        AssessmentResult::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'assessment_id' => $entryAssessment->id,
                'student_profile_id' => $studentProfile->id,
            ],
            [
                'enrollment_id' => $enrollment->id,
                'teacher_profile_id' => $teacherProfile->id,
                'score' => 7,
                'max_score' => 10,
                'level' => 'on_track',
                'status' => 'published',
                'feedback' => 'Nắm tốt khái niệm cơ bản, cần luyện thêm cách đặt câu hỏi cho AI.',
                'strengths' => 'Tò mò, chủ động thử nghiệm công cụ mới.',
                'improvements' => 'Cần viết prompt rõ mục tiêu và biết kiểm chứng kết quả.',
                'assessed_at' => now()->subDay(),
            ],
        );

        if ($firstSession) {
            $teacherComment = TeacherComment::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $studentProfile->id,
                    'class_session_id' => $firstSession->id,
                    'comment_type' => 'session',
                ],
                [
                    'title' => 'Nhận xét buổi học đầu tiên',
                    'enrollment_id' => $enrollment->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'visibility' => 'guardian',
                    'comment' => 'Học viên tham gia tích cực, đặt câu hỏi tốt và hoàn thành bài thực hành cơ bản.',
                    'rating' => 4,
                    'commented_at' => now(),
                ],
            );

            TeacherComment::query()
                ->where('tenant_id', $tenant->id)
                ->where('student_profile_id', $studentProfile->id)
                ->where('class_session_id', $firstSession->id)
                ->where('comment_type', 'session')
                ->where('id', '!=', $teacherComment->id)
                ->update(['visibility' => 'internal']);
        }

        $progressReport = ProgressReport::query()->updateOrCreate(
            [
                'tenant_id' => $tenant->id,
                'student_profile_id' => $studentProfile->id,
                'course_id' => $course->id,
                'report_period' => 'weekly',
            ],
            [
                'title' => 'Báo cáo tiến bộ tuần 1',
                'enrollment_id' => $enrollment->id,
                'class_group_id' => $classGroup->id,
                'teacher_profile_id' => $teacherProfile->id,
                'status' => 'published',
                'overall_level' => 'on_track',
                'progress_percent' => 35,
                'strengths' => 'Hiểu nhanh ví dụ thực tế, biết liên hệ AI với việc học.',
                'improvements' => 'Cần luyện thêm cấu trúc prompt và ghi chú sau mỗi buổi.',
                'recommendation' => 'Phụ huynh khuyến khích học viên hoàn thành worksheet trước buổi 2.',
                'published_at' => now(),
            ],
        );

        ProgressReport::query()
            ->where('tenant_id', $tenant->id)
            ->where('student_profile_id', $studentProfile->id)
            ->where('course_id', $course->id)
            ->where('report_period', 'weekly')
            ->where('id', '!=', $progressReport->id)
            ->update(['status' => 'archived']);

        foreach ([
            [
                'type' => 'schedule',
                'priority' => 'high',
                'title' => 'Nhắc lịch học buổi 2',
                'body' => 'Lớp AI Căn Bản học buổi 2 vào tối thứ 4, vui lòng chuẩn bị worksheet trước giờ học.',
            ],
            [
                'type' => 'finance',
                'priority' => 'normal',
                'title' => 'Cập nhật học phí',
                'body' => 'Trung tâm đã ghi nhận thanh toán một phần. Phần công nợ còn lại sẽ hiển thị trong mục học phí.',
            ],
            [
                'type' => 'progress',
                'priority' => 'normal',
                'title' => 'Đã có báo cáo tiến bộ tuần 1',
                'body' => 'Giáo viên đã cập nhật nhận xét và khuyến nghị học tập cho học viên.',
            ],
        ] as $notification) {
            $portalNotification = Notification::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $studentProfile->id,
                    'notification_type' => $notification['type'],
                    'channel' => 'portal',
                ],
                [
                    'title' => $notification['title'],
                    'person_id' => $studentPerson->id,
                    'audience_type' => 'student',
                    'body' => $notification['body'],
                    'status' => 'published',
                    'priority' => $notification['priority'],
                    'published_at' => now(),
                ],
            );

            Notification::query()
                ->where('tenant_id', $tenant->id)
                ->where('student_profile_id', $studentProfile->id)
                ->where('notification_type', $notification['type'])
                ->where('channel', 'portal')
                ->where('id', '!=', $portalNotification->id)
                ->update(['status' => 'archived']);
        }

        Certificate::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'certificate_code' => 'CERT-DAYAI-000001',
            ],
            [
                'student_profile_id' => $studentProfile->id,
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'class_group_id' => $classGroup->id,
                'verification_token' => 'verify-dayai-demo-000001',
                'title' => 'Chứng chỉ hoàn thành AI Căn Bản',
                'status' => 'issued',
                'final_score' => 8.5,
                'grade' => 'Giỏi',
                'issued_at' => now(),
                'notes' => 'Chứng chỉ demo Sprint 14.',
            ],
        );

        $payerPerson = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => 'parent.demo@dayai.edu.vn',
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'Phụ huynh Demo',
                'display_name' => 'Demo Parent',
                'phone' => '0901777000',
            ],
        );

        $customerAccount = CustomerAccount::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'person_id' => $payerPerson->id,
            ],
            [
                'branch_id' => $branch->id,
                'customer_code' => 'KH-000001',
                'account_type' => 'individual',
                'display_name' => $payerPerson->full_name,
                'phone' => $payerPerson->phone,
                'email' => $payerPerson->email,
                'status' => 'active',
            ],
        );

        $order = Order::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'order_code' => 'ORD-000001',
            ],
            [
                'branch_id' => $branch->id,
                'customer_account_id' => $customerAccount->id,
                'order_type' => 'b2c',
                'status' => 'confirmed',
                'ordered_at' => now(),
                'notes' => 'Đơn demo Sprint 4.',
                'created_by_id' => $user->id,
            ],
        );

        OrderItem::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'order_id' => $order->id,
                'course_id' => $course->id,
                'student_profile_id' => $studentProfile->id,
            ],
            [
                'class_group_id' => $classGroup->id,
                'description' => $course->name,
                'quantity' => 1,
                'unit_price_vnd' => $course->default_price_vnd,
                'discount_vnd' => 500000,
                'line_total_vnd' => max(0, $course->default_price_vnd - 500000),
            ],
        );

        $order->refresh();
        $order->recalculateTotals();
        $order->refresh();

        $invoice = Invoice::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'invoice_code' => 'INV-000001',
            ],
            [
                'order_id' => $order->id,
                'customer_account_id' => $customerAccount->id,
                'status' => 'issued',
                'issued_at' => now(),
                'due_date' => now()->addDays(7)->toDateString(),
                'amount_vnd' => $order->total_vnd,
                'paid_vnd' => 0,
                'balance_vnd' => $order->total_vnd,
                'notes' => 'Invoice demo Sprint 4.',
            ],
        );

        Receivable::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'invoice_id' => $invoice->id,
            ],
            [
                'customer_account_id' => $customerAccount->id,
                'status' => 'open',
                'original_amount_vnd' => $invoice->amount_vnd,
                'paid_vnd' => 0,
                'balance_vnd' => $invoice->amount_vnd,
                'due_date' => $invoice->due_date,
            ],
        );

        $payment = Payment::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'payment_code' => 'PAY-000001',
            ],
            [
                'invoice_id' => $invoice->id,
                'order_id' => $order->id,
                'customer_account_id' => $customerAccount->id,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'amount_vnd' => 1500000,
                'paid_at' => now(),
                'reference_no' => 'DEMO-S4-001',
                'notes' => 'Thanh toán một phần demo Sprint 4.',
                'created_by_id' => $user->id,
            ],
        );

        $invoice->refresh()->applyPayments();
        $order->refresh()->recalculateTotals();
        $payment->refresh();

        Receipt::query()->firstOrCreate(
            [
                'payment_id' => $payment->id,
            ],
            [
                'tenant_id' => $tenant->id,
                'receipt_code' => 'PT-000001',
                'issued_at' => $payment->paid_at,
                'issued_by_id' => $user->id,
                'payer_name' => $customerAccount->display_name,
                'amount_vnd' => $payment->amount_vnd,
                'content' => 'Phiếu thu demo Sprint 4.',
            ],
        );

        $enrollment->update(['order_id' => $order->id]);

        $company = Organization::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'tax_code' => 'EXAMPLE-CORP',
            ],
            [
                'branch_id' => $branch->id,
                'name' => 'Example Corp',
                'short_name' => 'EXAMPLE-CORP',
                'organization_type' => 'company',
                'industry' => 'Technology',
                'company_size' => '51-200',
                'phone' => '02839990000',
                'email' => 'hr@examplecorp.test',
                'status' => 'active',
                'notes' => 'Dữ liệu demo Sprint 11 cho cổng doanh nghiệp/HR.',
                'created_by_id' => $user->id,
                'updated_by_id' => $user->id,
            ],
        );

        $hrPerson = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => 'hr@examplecorp.test',
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'Phạm Thu Hà',
                'display_name' => 'HR Example',
                'phone' => '0901000004',
            ],
        );

        OrganizationContact::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'organization_id' => $company->id,
                'person_id' => $hrPerson->id,
            ],
            [
                'contact_role' => 'hr',
                'job_title' => 'HR Manager',
                'department' => 'People Operations',
                'is_primary' => true,
                'status' => 'active',
            ],
        );

        $companyCustomer = CustomerAccount::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'organization_id' => $company->id,
            ],
            [
                'branch_id' => $branch->id,
                'customer_code' => 'KH-B2B-000001',
                'account_type' => 'company',
                'display_name' => $company->name,
                'phone' => $company->phone,
                'email' => $company->email,
                'tax_code' => $company->tax_code,
                'status' => 'active',
            ],
        );

        $companyOrder = Order::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'order_code' => 'ORD-B2B-000001',
            ],
            [
                'branch_id' => $branch->id,
                'customer_account_id' => $companyCustomer->id,
                'lead_id' => $companyLead?->id,
                'order_type' => 'b2b',
                'status' => 'confirmed',
                'ordered_at' => now(),
                'notes' => 'Đơn demo Sprint 11: công ty mua khóa cho nhân sự.',
                'created_by_id' => $user->id,
            ],
        );

        foreach ([
            [
                'name' => 'Nguyễn B2B An',
                'email' => 'an@examplecorp.test',
                'phone' => '0901888101',
                'code' => 'HV-B2B-001',
                'job_title' => 'Sales Executive',
                'progress' => 45,
                'score' => 8,
                'level' => 'on_track',
                'attendance_status' => 'present',
            ],
            [
                'name' => 'Trần B2B Bình',
                'email' => 'binh@examplecorp.test',
                'phone' => '0901888102',
                'code' => 'HV-B2B-002',
                'job_title' => 'Marketing Specialist',
                'progress' => 30,
                'score' => 6,
                'level' => 'needs_support',
                'attendance_status' => 'late',
            ],
        ] as $companyLearner) {
            $learnerPerson = Person::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'email' => $companyLearner['email'],
                ],
                [
                    'branch_id' => $branch->id,
                    'full_name' => $companyLearner['name'],
                    'display_name' => $companyLearner['name'],
                    'phone' => $companyLearner['phone'],
                ],
            );

            $learnerProfile = StudentProfile::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'person_id' => $learnerPerson->id,
                ],
                [
                    'branch_id' => $branch->id,
                    'student_code' => $companyLearner['code'],
                    'student_type' => 'company_staff',
                    'organization_id' => $company->id,
                    'current_company' => $company->name,
                    'job_title' => $companyLearner['job_title'],
                    'learning_goal' => 'Ứng dụng AI vào công việc hằng ngày của phòng ban.',
                    'entry_level' => 'beginner',
                    'status' => 'active',
                ],
            );

            $companyEnrollment = Enrollment::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $learnerProfile->id,
                    'course_id' => $course->id,
                    'class_group_id' => $classGroup->id,
                ],
                [
                    'order_id' => $companyOrder->id,
                    'enrollment_code' => 'ENR-' . $companyLearner['code'],
                    'status' => 'active',
                    'enrolled_at' => now(),
                    'started_at' => now()->addWeek(),
                    'notes' => 'Dữ liệu demo Sprint 11 B2B.',
                ],
            );

            OrderItem::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'order_id' => $companyOrder->id,
                    'course_id' => $course->id,
                    'student_profile_id' => $learnerProfile->id,
                ],
                [
                    'class_group_id' => $classGroup->id,
                    'description' => $course->name . ' - ' . $learnerPerson->full_name,
                    'quantity' => 1,
                    'unit_price_vnd' => $course->default_price_vnd,
                    'discount_vnd' => 750000,
                    'line_total_vnd' => max(0, $course->default_price_vnd - 750000),
                ],
            );

            if ($firstSession) {
                AttendanceRecord::query()->firstOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'class_session_id' => $firstSession->id,
                        'student_profile_id' => $learnerProfile->id,
                    ],
                    [
                        'enrollment_id' => $companyEnrollment->id,
                        'status' => $companyLearner['attendance_status'],
                        'checked_in_at' => $firstSession->starts_at,
                        'checked_by_id' => $user->id,
                        'teacher_note' => 'Điểm danh demo Sprint 11 B2B.',
                    ],
                );
            }

            AssessmentResult::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'assessment_id' => $entryAssessment->id,
                    'student_profile_id' => $learnerProfile->id,
                ],
                [
                    'enrollment_id' => $companyEnrollment->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'score' => $companyLearner['score'],
                    'max_score' => 10,
                    'level' => $companyLearner['level'],
                    'status' => 'published',
                    'feedback' => 'Đánh giá demo cho nhân sự doanh nghiệp.',
                    'assessed_at' => now()->subDay(),
                ],
            );

            ProgressReport::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $learnerProfile->id,
                    'title' => 'Báo cáo tiến bộ B2B tuần 1',
                ],
                [
                    'enrollment_id' => $companyEnrollment->id,
                    'course_id' => $course->id,
                    'class_group_id' => $classGroup->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'report_period' => 'weekly',
                    'status' => 'published',
                    'overall_level' => $companyLearner['level'],
                    'progress_percent' => $companyLearner['progress'],
                    'strengths' => 'Có khả năng liên hệ bài học với công việc thực tế.',
                    'improvements' => 'Cần chuẩn hóa quy trình dùng AI theo phòng ban.',
                    'recommendation' => 'HR nên nhắc nhân sự hoàn thành bài thực hành trước buổi kế tiếp.',
                    'published_at' => now(),
                ],
            );

            Certificate::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'certificate_code' => 'CERT-' . $companyLearner['code'],
                ],
                [
                    'student_profile_id' => $learnerProfile->id,
                    'enrollment_id' => $companyEnrollment->id,
                    'course_id' => $course->id,
                    'class_group_id' => $classGroup->id,
                    'verification_token' => 'verify-' . strtolower($companyLearner['code']),
                    'title' => 'Chứng chỉ hoàn thành AI Cho Doanh Nghiệp',
                    'status' => 'issued',
                    'final_score' => $companyLearner['score'],
                    'grade' => $companyLearner['level'] === 'needs_support' ? 'Đạt' : 'Khá',
                    'issued_at' => now(),
                    'notes' => 'Chứng chỉ demo Sprint 14 cho nhân sự doanh nghiệp.',
                ],
            );

            $companyEnrollment->update(['order_id' => $companyOrder->id]);
        }

        $companyOrder->refresh()->recalculateTotals();
        $companyOrder->refresh();

        $companyInvoice = Invoice::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'invoice_code' => 'INV-B2B-000001',
            ],
            [
                'order_id' => $companyOrder->id,
                'customer_account_id' => $companyCustomer->id,
                'status' => 'issued',
                'issued_at' => now(),
                'due_date' => now()->addDays(14)->toDateString(),
                'amount_vnd' => $companyOrder->total_vnd,
                'paid_vnd' => 0,
                'balance_vnd' => $companyOrder->total_vnd,
                'notes' => 'Invoice demo Sprint 11 B2B.',
            ],
        );

        Receivable::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'invoice_id' => $companyInvoice->id,
            ],
            [
                'customer_account_id' => $companyCustomer->id,
                'status' => 'open',
                'original_amount_vnd' => $companyInvoice->amount_vnd,
                'paid_vnd' => 0,
                'balance_vnd' => $companyInvoice->amount_vnd,
                'due_date' => $companyInvoice->due_date,
            ],
        );

        $companyPayment = Payment::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'payment_code' => 'PAY-B2B-000001',
            ],
            [
                'invoice_id' => $companyInvoice->id,
                'order_id' => $companyOrder->id,
                'customer_account_id' => $companyCustomer->id,
                'payment_method' => 'bank_transfer',
                'status' => 'completed',
                'amount_vnd' => 3000000,
                'paid_at' => now(),
                'reference_no' => 'DEMO-S11-001',
                'notes' => 'Thanh toán một phần demo Sprint 11 B2B.',
                'created_by_id' => $user->id,
            ],
        );

        $companyInvoice->refresh()->applyPayments();
        $companyOrder->refresh()->recalculateTotals();
        $companyPayment->refresh();

        foreach ([
            [
                'type' => 'progress',
                'priority' => 'normal',
                'title' => 'Báo cáo nhóm tuần 1 đã sẵn sàng',
                'body' => 'HR có thể xem tiến độ trung bình và từng nhân sự trong cổng doanh nghiệp.',
            ],
            [
                'type' => 'finance',
                'priority' => 'high',
                'title' => 'Cập nhật công nợ đào tạo B2B',
                'body' => 'Example Corp đã thanh toán một phần đơn đào tạo. Công nợ còn lại được cập nhật trong portal.',
            ],
        ] as $notification) {
            Notification::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'organization_id' => $company->id,
                    'title' => $notification['title'],
                ],
                [
                    'person_id' => $hrPerson->id,
                    'audience_type' => 'company',
                    'notification_type' => $notification['type'],
                    'channel' => 'portal',
                    'body' => $notification['body'],
                    'status' => 'published',
                    'priority' => $notification['priority'],
                    'published_at' => now(),
                ],
            );
        }
    }
}
