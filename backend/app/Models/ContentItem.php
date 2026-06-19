<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentItem extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'content_category_id',
        'course_id',
        'author_id',
        'title',
        'slug',
        'content_type',
        'status',
        'excerpt',
        'body',
        'cover_image_url',
        'tags',
        'metadata',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'metadata' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function category(): BelongsTo { return $this->belongsTo(ContentCategory::class, 'content_category_id'); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function videoLessons(): HasMany { return $this->hasMany(VideoLesson::class); }
}
