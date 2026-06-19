<?php

namespace Tests\Feature;

use App\Models\ContentCategory;
use App\Models\ContentItem;
use App\Models\Course;
use App\Models\Tenant;
use App\Models\VideoLesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_content_api_returns_published_knowledge_and_videos(): void
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

        $category = ContentCategory::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Kho kiến thức AI',
            'slug' => 'kien-thuc-ai',
            'category_type' => 'knowledge',
            'is_active' => true,
        ]);

        ContentItem::query()->create([
            'tenant_id' => $tenant->id,
            'content_category_id' => $category->id,
            'course_id' => $course->id,
            'title' => 'AI là gì?',
            'slug' => 'ai-la-gi',
            'content_type' => 'article',
            'status' => 'published',
            'excerpt' => 'Bài nhập môn AI.',
            'body' => 'Nội dung.',
            'tags' => ['ai'],
            'published_at' => now(),
        ]);

        ContentItem::query()->create([
            'tenant_id' => $tenant->id,
            'content_category_id' => $category->id,
            'title' => 'Bài nháp không public',
            'slug' => 'bai-nhap-khong-public',
            'content_type' => 'article',
            'status' => 'draft',
        ]);

        VideoLesson::query()->create([
            'tenant_id' => $tenant->id,
            'course_id' => $course->id,
            'title' => 'Học thử AI',
            'slug' => 'hoc-thu-ai',
            'status' => 'published',
            'video_provider' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=demo',
            'duration_minutes' => 20,
            'access_level' => 'public',
            'summary' => 'Video học thử.',
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/content/home');

        $response
            ->assertOk()
            ->assertJsonPath('knowledge_items.0.title', 'AI là gì?')
            ->assertJsonPath('knowledge_items.0.category', 'Kho kiến thức AI')
            ->assertJsonPath('video_lessons.0.title', 'Học thử AI')
            ->assertJsonPath('video_lessons.0.access_level', 'public');

        $this->assertCount(1, $response->json('knowledge_items'));
        $this->assertCount(1, $response->json('video_lessons'));
    }
}
