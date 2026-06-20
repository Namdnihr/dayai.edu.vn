<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContentItem;
use App\Models\Tenant;
use App\Models\VideoLesson;
use Illuminate\Http\JsonResponse;

class PublicContentController extends Controller
{
    public function home(): JsonResponse
    {
        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();

        $knowledgeItems = ContentItem::query()
            ->with(['author:id,name', 'category:id,name,slug,category_type', 'course:id,name,slug,course_code'])
            ->where('tenant_id', $tenant->id)
            ->where('status', 'published')
            ->whereIn('content_type', ['article', 'checklist', 'case_study', 'prompt_library', 'news'])
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get()
            ->map(fn (ContentItem $item): array => [
                'title' => $item->title,
                'slug' => $item->slug,
                'content_type' => $item->content_type,
                'excerpt' => $item->excerpt,
                'category' => $item->category?->name,
                'course' => $item->course?->name,
                'published_at' => $item->published_at?->toDateString(),
                'tags' => $item->tags ?? [],
                'seo' => [
                    'title' => $item->seo_title,
                    'description' => $item->seo_description,
                    'canonical_url' => $item->canonical_url,
                    'og_image_url' => $item->og_image_url,
                ],
                'eeat' => [
                    'author' => $item->author?->name,
                    'expertise_level' => $item->expertise_level,
                    'reviewed_by' => $item->reviewed_by,
                    'reviewed_at' => $item->reviewed_at?->toDateString(),
                    'references' => $item->references ?? [],
                ],
            ]);

        $videoLessons = VideoLesson::query()
            ->with(['course:id,name,slug,course_code'])
            ->where('tenant_id', $tenant->id)
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->limit(6)
            ->get()
            ->map(fn (VideoLesson $video): array => [
                'title' => $video->title,
                'slug' => $video->slug,
                'summary' => $video->summary,
                'video_provider' => $video->video_provider,
                'video_url' => $video->video_url,
                'duration_minutes' => $video->duration_minutes,
                'access_level' => $video->access_level,
                'course' => $video->course?->name,
                'published_at' => $video->published_at?->toDateString(),
                'seo' => [
                    'title' => $video->seo_title,
                    'description' => $video->seo_description,
                    'thumbnail_url' => $video->thumbnail_url,
                    'canonical_url' => $video->canonical_url,
                ],
            ]);

        return response()->json([
            'knowledge_items' => $knowledgeItems,
            'video_lessons' => $videoLessons,
        ]);
    }
}
