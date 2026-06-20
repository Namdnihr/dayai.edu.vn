<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            $table->foreignUlid('course_module_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0)->after('course_module_id');
        });

        Schema::create('video_lesson_progress', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('video_lesson_id')->constrained()->cascadeOnDelete();
            $table->string('status', 50)->default('not_started');
            $table->unsignedInteger('progress_percent')->default(0);
            $table->unsignedInteger('last_position_seconds')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();

            $table->unique(['student_profile_id', 'video_lesson_id']);
            $table->index(['tenant_id', 'status']);
            $table->index(['enrollment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_lesson_progress');

        Schema::table('video_lessons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('course_module_id');
            $table->dropColumn('sort_order');
        });
    }
};
