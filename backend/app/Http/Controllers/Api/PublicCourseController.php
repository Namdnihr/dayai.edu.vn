<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;

class PublicCourseController extends Controller
{
    public function index(): JsonResponse
    {
        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();

        $courses = Course::query()
            ->where('tenant_id', $tenant->id)
            ->where('status', 'published')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (Course $course): array => $this->serializeCourse($course));

        return response()->json([
            'courses' => $courses,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $tenant = Tenant::query()->where('code', 'dayai')->firstOrFail();

        $course = Course::query()
            ->with(['modules:id,course_id,sort_order,title,description,duration_minutes,learning_objectives'])
            ->where('tenant_id', $tenant->id)
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'course' => $this->serializeCourse($course, includeModules: true),
        ]);
    }

    private function serializeCourse(Course $course, bool $includeModules = false): array
    {
        $payload = [
            'name' => $course->name,
            'subtitle' => $course->subtitle,
            'slug' => $course->slug,
            'course_code' => $course->course_code,
            'audience_type' => $course->audience_type,
            'level' => $course->level,
            'learning_format' => $course->learning_format,
            'short_description' => $course->short_description,
            'description' => $course->description,
            'outcomes' => $course->outcomes ?? [],
            'who_should_join' => $course->who_should_join ?? [],
            'prerequisites' => $course->prerequisites ?? [],
            'tools_covered' => $course->tools_covered ?? [],
            'duration_hours' => $course->duration_hours,
            'default_session_count' => $course->default_session_count,
            'default_price_vnd' => $course->default_price_vnd,
            'price_label' => $course->price_label,
            'thumbnail_url' => $course->thumbnail_url,
            'hero_image_url' => $course->hero_image_url,
            'primary_cta' => $course->primary_cta,
            'is_featured' => $course->is_featured,
            'seo' => [
                'title' => $course->seo_title,
                'description' => $course->seo_description,
                'canonical_url' => $course->canonical_url,
            ],
        ];

        if ($includeModules) {
            $payload['modules'] = $course->modules->map(fn ($module): array => [
                'sort_order' => $module->sort_order,
                'title' => $module->title,
                'description' => $module->description,
                'duration_minutes' => $module->duration_minutes,
                'learning_objectives' => $module->learning_objectives ?? [],
            ])->values();
        }

        return $payload;
    }
}
