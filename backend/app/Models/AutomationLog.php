<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomationLog extends Model
{
    use HasUlids;

    protected $fillable = [
        'tenant_id',
        'automation_workflow_id',
        'automation_message_id',
        'notification_id',
        'trigger_type',
        'audience_type',
        'subject_type',
        'subject_id',
        'recipient_type',
        'recipient_id',
        'channel',
        'status',
        'error_message',
        'payload',
        'scheduled_at',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'scheduled_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo { return $this->belongsTo(Tenant::class); }
    public function workflow(): BelongsTo { return $this->belongsTo(AutomationWorkflow::class, 'automation_workflow_id'); }
    public function message(): BelongsTo { return $this->belongsTo(AutomationMessage::class, 'automation_message_id'); }
    public function notification(): BelongsTo { return $this->belongsTo(Notification::class); }
}
