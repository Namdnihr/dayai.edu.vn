<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 80);
            $table->string('source_type', 50)->default('manual');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('lead_source_id')->nullable()->constrained()->nullOnDelete();
            $table->string('campaign_id')->nullable();
            $table->foreignUlid('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('lead_type', 50)->default('unknown');
            $table->string('status', 50)->default('new');
            $table->string('priority', 30)->default('normal');
            $table->string('full_name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('interested_course_id')->nullable();
            $table->text('learning_goal')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_contact_method', 50)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->text('lost_reason')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->foreignUlid('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUlid('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status', 'lead_type']);
            $table->index(['assigned_user_id', 'next_follow_up_at']);
            $table->index('phone');
            $table->index('email');
            $table->index('created_at');
        });

        Schema::create('lead_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assigned_to_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('assigned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'assigned_at']);
            $table->index('assigned_to_user_id');
        });

        Schema::create('consultation_activities', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('activity_type', 50)->default('call');
            $table->string('direction', 20)->nullable();
            $table->string('subject')->nullable();
            $table->text('content');
            $table->string('outcome', 80)->nullable();
            $table->timestamp('activity_at');
            $table->timestamp('next_follow_up_at')->nullable();
            $table->foreignUlid('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['lead_id', 'activity_at']);
            $table->index(['person_id', 'activity_at']);
            $table->index(['organization_id', 'activity_at']);
            $table->index('created_by_id');
        });

        Schema::create('trial_registrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('student_profile_id')->nullable();
            $table->string('course_id')->nullable();
            $table->date('preferred_date')->nullable();
            $table->string('preferred_time', 100)->nullable();
            $table->string('scheduled_session_id')->nullable();
            $table->string('status', 50)->default('requested');
            $table->text('note')->nullable();
            $table->foreignUlid('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status', 'preferred_date']);
            $table->index('lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_registrations');
        Schema::dropIfExists('consultation_activities');
        Schema::dropIfExists('lead_assignments');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('lead_sources');
    }
};
