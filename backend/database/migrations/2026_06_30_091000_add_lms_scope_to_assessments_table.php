<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table): void {
            $table->foreignUlid('course_module_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
            $table->foreignUlid('video_lesson_id')->nullable()->after('course_module_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('video_lesson_id');
            $table->dropConstrainedForeignId('course_module_id');
        });
    }
};
