<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('attempt_code')->unique();
            $table->unsignedInteger('attempt_no')->default(1);
            $table->string('status')->default('in_progress');
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->default(0);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('question_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['student_profile_id', 'assessment_id']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('quiz_attempt_answers', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('quiz_attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assessment_question_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('question_id')->constrained()->cascadeOnDelete();
            $table->jsonb('selected_option_ids')->nullable();
            $table->text('answer_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->decimal('score_awarded', 8, 2)->default(0);
            $table->text('teacher_feedback')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->unique(['quiz_attempt_id', 'assessment_question_id']);
            $table->index(['question_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
    }
};
