<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoLessonProgress extends Model
{
    use HasUlids;

    protected $table = 'video_lesson_progress';

    protected $fillable = [
        'tenant_id',
        'student_profile_id',
        'enrollment_id',
        'video_lesson_id',
        'status',
        'progress_percent',
        'last_position_seconds',
        'interaction_state',
        'learner_notes',
        'started_at',
        'completed_at',
        'last_watched_at',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'integer',
            'last_position_seconds' => 'integer',
            'interaction_state' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'last_watched_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function studentProfile(): BelongsTo { return $this->belongsTo(StudentProfile::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function videoLesson(): BelongsTo { return $this->belongsTo(VideoLesson::class); }
}
