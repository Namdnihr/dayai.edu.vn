<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('class_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('assessment_type')->default('progress');
            $table->string('status')->default('draft');
            $table->decimal('max_score', 8, 2)->default(10);
            $table->decimal('weight_percent', 5, 2)->nullable();
            $table->timestamp('assessment_at')->nullable();
            $table->text('description')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'assessment_type']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('assessment_results', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('teacher_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->default(10);
            $table->string('level')->nullable();
            $table->string('status')->default('draft');
            $table->text('feedback')->nullable();
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['student_profile_id', 'assessed_at']);
        });

        Schema::create('teacher_comments', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('class_session_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('teacher_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('comment_type')->default('progress');
            $table->string('visibility')->default('guardian');
            $table->string('title')->nullable();
            $table->text('comment');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamp('commented_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'visibility']);
            $table->index(['student_profile_id', 'commented_at']);
        });

        Schema::create('progress_reports', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('class_group_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('teacher_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('report_period')->default('weekly');
            $table->string('title');
            $table->string('status')->default('draft');
            $table->string('overall_level')->nullable();
            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['student_profile_id', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_reports');
        Schema::dropIfExists('teacher_comments');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessments');
    }
};
