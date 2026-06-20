<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCourseApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_courses_api_returns_published_courses(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Căn Bản',
            'subtitle' => 'Lộ trình nhập môn AI cho người mới',
            'slug' => 'ai-can-ban',
            'course_code' => 'AI-FUNDAMENTALS',
            'audience_type' => 'work',
            'level' => 'beginner',
            'learning_format' => 'hybrid',
            'short_description' => 'Học AI bài bản từ nền tảng đến ứng dụng.',
            'outcomes' => ['Biết dùng ChatGPT đúng cách'],
            'who_should_join' => ['Người đi làm muốn tăng năng suất'],
            'tools_covered' => ['ChatGPT', 'Canva AI'],
            'default_price_vnd' => 3500000,
            'price_label' => '3.500.000đ',
            'primary_cta' => 'Đăng ký tư vấn',
            'is_featured' => true,
            'sort_order' => 1,
            'seo_title' => 'Khóa học AI căn bản',
            'status' => 'published',
        ]);

        Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Khóa nháp',
            'slug' => 'khoa-nhap',
            'course_code' => 'DRAFT',
            'default_price_vnd' => 0,
            'status' => 'draft',
        ]);

        $response = $this->getJson('/api/courses');

        $response
            ->assertOk()
            ->assertJsonPath('courses.0.name', 'AI Căn Bản')
            ->assertJsonPath('courses.0.slug', 'ai-can-ban')
            ->assertJsonPath('courses.0.audience_type', 'work')
            ->assertJsonPath('courses.0.seo.title', 'Khóa học AI căn bản');

        $this->assertCount(1, $response->json('courses'));
    }

    public function test_public_course_detail_api_returns_modules(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
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

        CourseModule::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'sort_order' => 1,
            'title' => 'Tư duy AI nền tảng',
            'description' => 'Hiểu AI là gì và dùng AI an toàn.',
            'duration_minutes' => 90,
            'learning_objectives' => ['Hiểu cách đặt câu hỏi cho AI'],
        ]);

        $response = $this->getJson('/api/courses/ai-can-ban');

        $response
            ->assertOk()
            ->assertJsonPath('course.name', 'AI Căn Bản')
            ->assertJsonPath('course.modules.0.title', 'Tư duy AI nền tảng')
            ->assertJsonPath('course.modules.0.duration_minutes', 90);
    }
}
