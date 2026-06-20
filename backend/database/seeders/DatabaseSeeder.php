<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\AssessmentResult;
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
                'name' => 'CÆ¡ sá»Ÿ chÃ­nh',
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

        $sources$sources = collect([
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
                'full_name' => 'Nguyá»…n Minh Anh',
                'phone' => '0901000001',
                'email' => 'minhanh.parent@example.com',
                'learning_goal' => 'Muá»‘n tÃ¬m khÃ³a AI cho con lá»›p 7.',
                'message' => 'Cáº§n tÆ° váº¥n lá»™ trÃ¬nh há»c thá»­ cuá»‘i tuáº§n.',
                'source' => 'website',
                'next_follow_up_at' => now()->addDay(),
            ],
            [
                'lead_type' => 'student',
                'status' => 'contacting',
                'priority' => 'normal',
                'full_name' => 'Tráº§n Quá»‘c Báº£o',
                'phone' => '0901000002',
                'email' => 'baostudent@example.com',
                'learning_goal' => 'Muá»‘n há»c AI Ä‘á»ƒ lÃ m Ä‘á»“ Ã¡n Ä‘áº¡i há»c.',
                'message' => 'Quan tÃ¢m khÃ³a AI á»©ng dá»¥ng cho sinh viÃªn.',
                'source' => 'facebook',
                'last_contacted_at' => now()->subHours(5),
                'next_follow_up_at' => now()->addDays(2),
            ],
            [
                'lead_type' => 'business_owner',
                'status' => 'consulting',
                'priority' => 'urgent',
                'full_name' => 'LÃª HoÃ ng Nam',
                'phone' => '0901000003',
                'email' => 'nam.ceo@example.com',
                'company_name' => 'Nam Digital',
                'learning_goal' => 'á»¨ng dá»¥ng AI vÃ o marketing vÃ  váº­n hÃ nh.',
                'message' => 'Chá»§ doanh nghiá»‡p muá»‘n há»c lá»›p tá»‘i.',
                'source' => 'zalo',
                'last_contacted_at' => now()->subDay(),
                'next_follow_up_at' => now()->addHours(6),
            ],
            [
                'lead_type' => 'company',
                'status' => 'trial_scheduled',
                'priority' => 'high',
                'full_name' => 'Pháº¡m Thu HÃ ',
                'phone' => '0901000004',
                'email' => 'hr@examplecorp.test',
                'company_name' => 'Example Corp',
                'learning_goal' => 'ÄÃ o táº¡o AI cho Ä‘á»™i sales vÃ  marketing.',
                'message' => 'CÃ´ng ty muá»‘n mua khÃ³a cho 20 nhÃ¢n sá»±.',
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
                    'note' => 'Dá»¯ liá»‡u demo Sprint 2',
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
                    'subject' => 'Gá»i HR xÃ¡c nháº­n nhu cáº§u Ä‘Ã o táº¡o',
                ],
                [
                    'organization_id' => $companyLead->organization_id,
                    'direction' => 'outbound',
                    'content' => 'HR quan tÃ¢m lá»›p AI á»©ng dá»¥ng cho team sales/marketing, cáº§n Ä‘á» xuáº¥t lá»‹ch há»c thá»­.',
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
                    'note' => 'Demo há»c thá»­ cho nhÃ³m cÃ´ng ty.',
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
                'full_name' => 'Giáº£ng viÃªn DAYAI',
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
                'bio' => 'Giáº£ng viÃªn phá»¥ trÃ¡ch cÃ¡c lá»›p AI á»©ng dá»¥ng.',
                'specialties' => ['AI cÄƒn báº£n', 'Prompt Engineering', 'Tá»± Ä‘á»™ng hÃ³a'],
                'status' => 'active',
            ],
        );

        $course = Course::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'course_code' => 'AI-FUNDAMENTALS',
            ],
            [
                'name' => 'AI CÄƒn Báº£n Cho NgÆ°á»i Má»›i',
                'slug' => 'ai-can-ban-cho-nguoi-moi',
                'audience_type' => 'mixed',
                'level' => 'beginner',
                'short_description' => 'KhÃ³a nháº­p mÃ´n giÃºp há»c viÃªn hiá»ƒu vÃ  á»©ng dá»¥ng AI vÃ o há»c táº­p/cÃ´ng viá»‡c.',
                'description' => 'Há»c viÃªn lÃ m quen vá»›i AI, prompt, cÃ´ng cá»¥ phá»• biáº¿n vÃ  má»™t sá»‘ bÃ i thá»±c hÃ nh á»©ng dá»¥ng.',
                'outcomes' => ['Hiá»ƒu ná»n táº£ng AI', 'Viáº¿t prompt hiá»‡u quáº£', 'á»¨ng dá»¥ng AI vÃ o cÃ´ng viá»‡c háº±ng ngÃ y'],
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
                'name' => 'Kho kiáº¿n thá»©c AI',
                'category_type' => 'knowledge',
                'description' => 'BÃ i viáº¿t, checklist, prompt máº«u vÃ  case study AI.',
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
                'description' => 'Video bÃ i há»c, workshop replay vÃ  há»c thá»­.',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        foreach ([
            [
                'title' => 'AI lÃ  gÃ¬ vÃ  ngÆ°á»i má»›i nÃªn báº¯t Ä‘áº§u tá»« Ä‘Ã¢u?',
                'slug' => 'ai-la-gi-nguoi-moi-bat-dau-tu-dau',
                'content_type' => 'article',
                'excerpt' => 'BÃ i nháº­p mÃ´n giÃºp phá»¥ huynh, sinh viÃªn vÃ  ngÆ°á»i Ä‘i lÃ m hiá»ƒu cÃ¡ch báº¯t Ä‘áº§u há»c AI.',
            ],
            [
                'title' => 'Checklist á»©ng dá»¥ng AI cho há»c táº­p vÃ  cÃ´ng viá»‡c',
                'slug' => 'checklist-ung-dung-ai-hoc-tap-cong-viec',
                'content_type' => 'checklist',
                'excerpt' => 'Danh sÃ¡ch viá»‡c cáº§n chuáº©n bá»‹ trÆ°á»›c khi dÃ¹ng AI vÃ o há»c táº­p, bÃ¡o cÃ¡o vÃ  tá»± Ä‘á»™ng hÃ³a.',
            ],
            [
                'title' => '10 prompt máº«u cho ngÆ°á»i má»›i há»c AI',
                'slug' => '10-prompt-mau-cho-nguoi-moi-hoc-ai',
                'content_type' => 'prompt_library',
                'excerpt' => 'Bá»™ prompt máº«u Ä‘á»ƒ ngÆ°á»i há»c thá»±c hÃ nh ngay sau buá»•i Ä‘áº§u tiÃªn.',
            ],
        ] as $contentItem) {
            ContentItem::query()->firstOrCreate(
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
                    'body' => 'Ná»™i dung demo Sprint 7. Khi triá»ƒn khai tháº­t, Ä‘á»™i ná»™i dung sáº½ thay báº±ng bÃ i viáº¿t Ä‘áº§y Ä‘á»§.',
                    'tags' => ['ai', 'dayai', 'mvp'],
                    'published_at' => now(),
                ],
            );
        }

        foreach ([
            [
                'title' => 'Há»c thá»­: AI cÄƒn báº£n trong 20 phÃºt',
                'slug' => 'hoc-thu-ai-can-ban-20-phut',
                'duration_minutes' => 20,
                'access_level' => 'public',
            ],
            [
                'title' => 'Workshop replay: Prompt Engineering cho ngÆ°á»i má»›i',
                'slug' => 'workshop-replay-prompt-engineering-nguoi-moi',
                'duration_minutes' => 45,
                'access_level' => 'lead_magnet',
            ],
            [
                'title' => 'BÃ i táº­p thá»±c hÃ nh: táº¡o trá»£ lÃ½ há»c táº­p báº±ng AI',
                'slug' => 'bai-tap-tao-tro-ly-hoc-tap-bang-ai',
                'duration_minutes' => 30,
                'access_level' => 'student',
            ],
        ] as $videoLesson) {
            VideoLesson::query()->firstOrCreate(
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
                    'summary' => 'Video demo cho module Video Academy cá»§a DAYAI.',
                    'resources' => ['worksheet' => 'Demo worksheet'],
                    'published_at' => now(),
                    'metadata' => ['category_id' => $videoCategory->id],
                ],
            );
        }

        foreach ([
            ['sort_order' => 1, 'title' => 'Tá»•ng quan AI vÃ  á»©ng dá»¥ng thá»±c táº¿', 'duration_minutes' => 120],
            ['sort_order' => 2, 'title' => 'Prompt Engineering cÄƒn báº£n', 'duration_minutes' => 120],
            ['sort_order' => 3, 'title' => 'Thá»±c hÃ nh AI cho há»c táº­p vÃ  cÃ´ng viá»‡c', 'duration_minutes' => 120],
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
                'name' => 'AI CÄƒn Báº£n - Lá»›p tá»‘i T2/T4',
                'learning_format' => 'hybrid',
                'start_date' => now()->addWeek()->toDateString(),
                'end_date' => now()->addWeeks(4)->toDateString(),
                'max_students' => 20,
                'status' => 'enrolling',
                'schedule_note' => 'Tá»‘i thá»© 2/4, 19:30 - 21:30',
                'location' => 'DAYAI Main / Google Meet',
            ],
        );

        $modules = $course->modules()->take(2)->get()->values();

        foreach ([1, 2] as $sessionNo) {
            ClassSession::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'class_group_id' => $classGroup->id,
                    'session_no' => $sessionNo,
                ],
                [
                    'course_module_id' => $modules->get($sessionNo - 1)?->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'title' => $modules->get($sessionNo - 1)?->title ?? "Buá»•i {$sessionNo}",
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
                'full_name' => 'Há»c viÃªn Demo',
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
                'learning_goal' => 'Há»c AI Ä‘á»ƒ Ã¡p dá»¥ng vÃ o há»c táº­p vÃ  Ä‘á»“ Ã¡n.',
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
                'notes' => 'Dá»¯ liá»‡u demo Sprint 3',
            ],
        );

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
                    'teacher_note' => 'Äiá»ƒm danh demo Sprint 3.',
                ],
            );
        }

        $entryAssessment = Assessment::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'class_group_id' => $classGroup->id,
                'title' => 'ÄÃ¡nh giÃ¡ Ä‘áº§u vÃ o AI CÄƒn Báº£n',
            ],
            [
                'course_id' => $course->id,
                'assessment_type' => 'entry',
                'status' => 'published',
                'max_score' => 10,
                'weight_percent' => 20,
                'assessment_at' => now()->subDays(2),
                'description' => 'Kiá»ƒm tra tÆ° duy logic, kháº£ nÄƒng dÃ¹ng cÃ´ng cá»¥ AI vÃ  má»¥c tiÃªu há»c táº­p.',
            ],
        );

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
                'feedback' => 'Náº¯m tá»‘t khÃ¡i niá»‡m cÆ¡ báº£n, cáº§n luyá»‡n thÃªm cÃ¡ch Ä‘áº·t cÃ¢u há»i cho AI.',
                'strengths' => 'TÃ² mÃ², chá»§ Ä‘á»™ng thá»­ nghiá»‡m cÃ´ng cá»¥ má»›i.',
                'improvements' => 'Cáº§n viáº¿t prompt rÃµ má»¥c tiÃªu vÃ  biáº¿t kiá»ƒm chá»©ng káº¿t quáº£.',
                'assessed_at' => now()->subDay(),
            ],
        );

        if ($firstSession) {
            TeacherComment::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $studentProfile->id,
                    'class_session_id' => $firstSession->id,
                    'title' => 'Nháº­n xÃ©t buá»•i há»c Ä‘áº§u tiÃªn',
                ],
                [
                    'enrollment_id' => $enrollment->id,
                    'teacher_profile_id' => $teacherProfile->id,
                    'comment_type' => 'session',
                    'visibility' => 'guardian',
                    'comment' => 'Há»c viÃªn tham gia tÃ­ch cá»±c, Ä‘áº·t cÃ¢u há»i tá»‘t vÃ  hoÃ n thÃ nh bÃ i thá»±c hÃ nh cÆ¡ báº£n.',
                    'rating' => 4,
                    'commented_at' => now(),
                ],
            );
        }

        ProgressReport::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'student_profile_id' => $studentProfile->id,
                'title' => 'BÃ¡o cÃ¡o tiáº¿n bá»™ tuáº§n 1',
            ],
            [
                'enrollment_id' => $enrollment->id,
                'course_id' => $course->id,
                'class_group_id' => $classGroup->id,
                'teacher_profile_id' => $teacherProfile->id,
                'report_period' => 'weekly',
                'status' => 'published',
                'overall_level' => 'on_track',
                'progress_percent' => 35,
                'strengths' => 'Hiá»ƒu nhanh vÃ­ dá»¥ thá»±c táº¿, biáº¿t liÃªn há»‡ AI vá»›i viá»‡c há»c.',
                'improvements' => 'Cáº§n luyá»‡n thÃªm cáº¥u trÃºc prompt vÃ  ghi chÃº sau má»—i buá»•i.',
                'recommendation' => 'Phá»¥ huynh khuyáº¿n khÃ­ch há»c viÃªn hoÃ n thÃ nh worksheet trÆ°á»›c buá»•i 2.',
                'published_at' => now(),
            ],
        );

        foreach ([
            [
                'type' => 'schedule',
                'priority' => 'high',
                'title' => 'Nháº¯c lá»‹ch há»c buá»•i 2',
                'body' => 'Lá»›p AI CÄƒn Báº£n há»c buá»•i 2 vÃ o tá»‘i thá»© 4, vui lÃ²ng chuáº©n bá»‹ worksheet trÆ°á»›c giá» há»c.',
            ],
            [
                'type' => 'finance',
                'priority' => 'normal',
                'title' => 'Cáº­p nháº­t há»c phÃ­',
                'body' => 'Trung tÃ¢m Ä‘Ã£ ghi nháº­n thanh toÃ¡n má»™t pháº§n. Pháº§n cÃ´ng ná»£ cÃ²n láº¡i sáº½ hiá»ƒn thá»‹ trong má»¥c há»c phÃ­.',
            ],
            [
                'type' => 'progress',
                'priority' => 'normal',
                'title' => 'ÄÃ£ cÃ³ bÃ¡o cÃ¡o tiáº¿n bá»™ tuáº§n 1',
                'body' => 'GiÃ¡o viÃªn Ä‘Ã£ cáº­p nháº­t nháº­n xÃ©t vÃ  khuyáº¿n nghá»‹ há»c táº­p cho há»c viÃªn.',
            ],
        ] as $notification) {
            Notification::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $studentProfile->id,
                    'title' => $notification['title'],
                ],
                [
                    'person_id' => $studentPerson->id,
                    'audience_type' => 'student',
                    'notification_type' => $notification['type'],
                    'channel' => 'portal',
                    'body' => $notification['body'],
                    'status' => 'published',
                    'priority' => $notification['priority'],
                    'published_at' => now(),
                ],
            );
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
                'title' => 'Chá»©ng chá»‰ hoÃ n thÃ nh AI CÄƒn Báº£n',
                'status' => 'issued',
                'final_score' => 8.5,
                'grade' => 'Giá»i',
                'issued_at' => now(),
                'notes' => 'Chá»©ng chá»‰ demo Sprint 14.',
            ],
        );

        $payerPerson = Person::query()->firstOrCreate(
            [
                'tenant_id' => $tenant->id,
                'email' => 'parent.demo@dayai.edu.vn',
            ],
            [
                'branch_id' => $branch->id,
                'full_name' => 'Phá»¥ huynh Demo',
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
                'notes' => 'ÄÆ¡n demo Sprint 4.',
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
                'notes' => 'Thanh toÃ¡n má»™t pháº§n demo Sprint 4.',
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
                'content' => 'Phiáº¿u thu demo Sprint 4.',
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
                'notes' => 'Dá»¯ liá»‡u demo Sprint 11 cho cá»•ng doanh nghiá»‡p/HR.',
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
                'full_name' => 'Pháº¡m Thu HÃ ',
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
                'notes' => 'ÄÆ¡n demo Sprint 11: cÃ´ng ty mua khÃ³a cho nhÃ¢n sá»±.',
                'created_by_id' => $user->id,
            ],
        );

        foreach ([
            [
                'name' => 'Nguyá»…n B2B An',
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
                'name' => 'Tráº§n B2B BÃ¬nh',
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
                    'learning_goal' => 'á»¨ng dá»¥ng AI vÃ o cÃ´ng viá»‡c háº±ng ngÃ y cá»§a phÃ²ng ban.',
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
                    'notes' => 'Dá»¯ liá»‡u demo Sprint 11 B2B.',
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
                        'teacher_note' => 'Äiá»ƒm danh demo Sprint 11 B2B.',
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
                    'feedback' => 'ÄÃ¡nh giÃ¡ demo cho nhÃ¢n sá»± doanh nghiá»‡p.',
                    'assessed_at' => now()->subDay(),
                ],
            );

            ProgressReport::query()->firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'student_profile_id' => $learnerProfile->id,
                    'title' => 'BÃ¡o cÃ¡o tiáº¿n bá»™ B2B tuáº§n 1',
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
                    'strengths' => 'CÃ³ kháº£ nÄƒng liÃªn há»‡ bÃ i há»c vá»›i cÃ´ng viá»‡c thá»±c táº¿.',
                    'improvements' => 'Cáº§n chuáº©n hÃ³a quy trÃ¬nh dÃ¹ng AI theo phÃ²ng ban.',
                    'recommendation' => 'HR nÃªn nháº¯c nhÃ¢n sá»± hoÃ n thÃ nh bÃ i thá»±c hÃ nh trÆ°á»›c buá»•i káº¿ tiáº¿p.',
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
                    'title' => 'Chá»©ng chá»‰ hoÃ n thÃ nh AI Cho Doanh Nghiá»‡p',
                    'status' => 'issued',
                    'final_score' => $companyLearner['score'],
                    'grade' => $companyLearner['level'] === 'needs_support' ? 'Äáº¡t' : 'KhÃ¡',
                    'issued_at' => now(),
                    'notes' => 'Chá»©ng chá»‰ demo Sprint 14 cho nhÃ¢n sá»± doanh nghiá»‡p.',
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
                'notes' => 'Thanh toÃ¡n má»™t pháº§n demo Sprint 11 B2B.',
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
                'title' => 'BÃ¡o cÃ¡o nhÃ³m tuáº§n 1 Ä‘Ã£ sáºµn sÃ ng',
                'body' => 'HR cÃ³ thá»ƒ xem tiáº¿n Ä‘á»™ trung bÃ¬nh vÃ  tá»«ng nhÃ¢n sá»± trong cá»•ng doanh nghiá»‡p.',
            ],
            [
                'type' => 'finance',
                'priority' => 'high',
                'title' => 'Cáº­p nháº­t cÃ´ng ná»£ Ä‘Ã o táº¡o B2B',
                'body' => 'Example Corp Ä‘Ã£ thanh toÃ¡n má»™t pháº§n Ä‘Æ¡n Ä‘Ã o táº¡o. CÃ´ng ná»£ cÃ²n láº¡i Ä‘Æ°á»£c cáº­p nháº­t trong portal.',
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
