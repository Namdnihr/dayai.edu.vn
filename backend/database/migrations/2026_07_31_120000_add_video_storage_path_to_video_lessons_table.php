<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('video_lessons', function (Blueprint $table): void {
            $table->string('video_storage_path', 1000)->nullable()->after('video_url');
        });
    }

    public function down(): void
    {
        Schema::table('video_lessons', function (Blueprint $table): void {
            $table->dropColumn('video_storage_path');
        });
    }
};
