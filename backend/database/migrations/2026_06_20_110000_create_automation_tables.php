<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('automation_workflows', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('trigger_type', 80);
            $table->string('audience_type', 50)->default('lead');
            $table->string('channel', 50)->default('portal');
            $table->string('status', 30)->default('active');
            $table->unsignedInteger('cooldown_hours')->default(24);
            $table->jsonb('conditions')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'trigger_type', 'status']);
        });

        Schema::create('automation_messages', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('automation_workflow_id')->constrained()->cascadeOnDelete();
            $table->string('message_type', 50)->default('notification');
            $table->string('channel', 50)->default('portal');
            $table->string('title_template');
            $table->text('body_template');
            $table->string('priority', 30)->default('normal');
            $table->string('status', 30)->default('active');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['automation_workflow_id', 'status']);
        });

        Schema::create('automation_logs', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('automation_workflow_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('automation_message_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('notification_id')->nullable()->constrained()->nullOnDelete();
            $table->string('trigger_type', 80);
            $table->string('audience_type', 50);
            $table->string('subject_type');
            $table->string('subject_id');
            $table->string('recipient_type')->nullable();
            $table->string('recipient_id')->nullable();
            $table->string('channel', 50)->default('portal');
            $table->string('status', 30)->default('pending');
            $table->text('error_message')->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['automation_workflow_id', 'subject_type', 'subject_id', 'recipient_type', 'recipient_id'], 'automation_logs_dedupe_unique');
            $table->index(['tenant_id', 'status', 'created_at']);
            $table->index(['trigger_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automation_logs');
        Schema::dropIfExists('automation_messages');
        Schema::dropIfExists('automation_workflows');
    }
};
