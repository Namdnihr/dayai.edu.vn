<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_lesson_progress', function (Blueprint $table): void {
            $table->jsonb('interaction_state')->nullable()->after('last_position_seconds');
            $table->text('learner_notes')->nullable()->after('interaction_state');
        });
    }

    public function down(): void
    {
        Schema::table('video_lesson_progress', function (Blueprint $table): void {
            $table->dropColumn(['interaction_state', 'learner_notes']);
        });
    }
};
