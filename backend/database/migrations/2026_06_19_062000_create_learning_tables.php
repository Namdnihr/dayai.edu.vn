<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_profiles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('teacher_code', 50);
            $table->string('title')->nullable();
            $table->text('bio')->nullable();
            $table->jsonb('specialties')->nullable();
            $table->string('status', 50)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'teacher_code']);
            $table->unique(['tenant_id', 'person_id']);
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('course_code', 50);
            $table->string('audience_type', 50)->default('mixed');
            $table->string('level', 50)->default('beginner');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->jsonb('outcomes')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->integer('default_session_count')->nullable();
            $table->bigInteger('default_price_vnd')->default(0);
            $table->string('status', 50)->default('draft');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->unique(['tenant_id', 'course_code']);
            $table->index(['tenant_id', 'audience_type', 'status']);
        });

        Schema::create('course_modules', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->integer('sort_order')->default(1);
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->jsonb('learning_objectives')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('course_id');
        });

        Schema::create('class_groups', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('teacher_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('class_code', 50);
            $table->string('name');
            $table->string('learning_format', 50)->default('offline');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('max_students')->nullable();
            $table->string('status', 50)->default('planned');
            $table->text('schedule_note')->nullable();
            $table->string('location')->nullable();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'class_code']);
            $table->index(['tenant_id', 'status', 'start_date']);
            $table->index('course_id');
            $table->index('teacher_profile_id');
            $table->index('organization_id');
        });

        Schema::create('class_sessions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('class_group_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('teacher_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('session_no');
            $table->string('title')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('status', 50)->default('scheduled');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_group_id', 'session_no']);
            $table->index(['class_group_id', 'starts_at']);
            $table->index(['teacher_profile_id', 'starts_at']);
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('class_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_id')->nullable();
            $table->string('enrollment_code', 50);
            $table->string('status', 50)->default('active');
            $table->timestamp('enrolled_at');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'enrollment_code']);
            $table->unique(['student_profile_id', 'course_id', 'class_group_id']);
            $table->index(['student_profile_id', 'status']);
            $table->index(['class_group_id', 'status']);
        });

        Schema::create('attendance_records', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('class_session_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status', 50)->default('present');
            $table->timestamp('checked_in_at')->nullable();
            $table->integer('minutes_late')->nullable();
            $table->text('absence_reason')->nullable();
            $table->text('teacher_note')->nullable();
            $table->foreignUlid('checked_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['class_session_id', 'student_profile_id']);
            $table->index(['student_profile_id', 'status']);
            $table->index('enrollment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('class_sessions');
        Schema::dropIfExists('class_groups');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('teacher_profiles');
    }
};
