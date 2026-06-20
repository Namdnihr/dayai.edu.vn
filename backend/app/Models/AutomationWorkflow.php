<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutomationWorkflow extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'trigger_type',
        'audience_type',
        'channel',
        'status',
        'cooldown_hours',
        'conditions',
        'metadata',
        'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'cooldown_hours' => 'integer',
            'conditions' => 'array',
            'metadata' => 'array',
            'last_run_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function messages(): HasMany { return $this->hasMany(AutomationMessage::class); }
    public function logs(): HasMany { return $this->hasMany(AutomationLog::class); }
}
