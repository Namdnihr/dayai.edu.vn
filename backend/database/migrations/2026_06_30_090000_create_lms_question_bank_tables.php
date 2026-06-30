<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_banks', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('video_lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('bank_type')->default('course');
            $table->string('audience_type')->nullable();
            $table->string('level')->nullable();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->jsonb('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'bank_type']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('questions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('question_bank_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_module_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('video_lesson_id')->nullable()->constrained()->nullOnDelete();
            $table->string('question_type')->default('single_choice');
            $table->string('difficulty')->default('easy');
            $table->string('status')->default('draft');
            $table->text('prompt');
            $table->text('explanation')->nullable();
            $table->decimal('default_score', 8, 2)->default(1);
            $table->unsignedInteger('time_limit_seconds')->nullable();
            $table->jsonb('tags')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'question_type']);
            $table->index(['tenant_id', 'difficulty']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('question_options', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('question_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(1);
            $table->text('content');
            $table->boolean('is_correct')->default(false);
            $table->text('feedback')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['question_id', 'sort_order']);
        });

        Schema::create('assessment_questions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('question_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(1);
            $table->decimal('score', 8, 2)->default(1);
            $table->boolean('is_required')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->unique(['assessment_id', 'question_id']);
            $table->index(['assessment_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_questions');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('question_banks');
    }
};
