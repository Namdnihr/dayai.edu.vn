<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoLesson extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'content_item_id',
        'course_id',
        'title',
        'slug',
        'status',
        'video_provider',
        'video_url',
        'duration_minutes',
        'access_level',
        'summary',
        'seo_title',
        'seo_description',
        'thumbnail_url',
        'canonical_url',
        'resources',
        'metadata',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'duration_minutes' => 'integer',
            'resources' => 'array',
            'metadata' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function contentItem(): BelongsTo { return $this->belongsTo(ContentItem::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}
