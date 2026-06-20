<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('content_categories', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('description');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
            $table->string('canonical_url', 1000)->nullable()->after('seo_description');
        });

        Schema::table('content_items', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('cover_image_url');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
            $table->string('canonical_url', 1000)->nullable()->after('seo_description');
            $table->string('og_image_url', 1000)->nullable()->after('canonical_url');
            $table->string('expertise_level', 50)->default('beginner')->after('og_image_url');
            $table->string('reviewed_by')->nullable()->after('expertise_level');
            $table->date('reviewed_at')->nullable()->after('reviewed_by');
            $table->jsonb('references')->nullable()->after('reviewed_at');
        });

        Schema::table('video_lessons', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('summary');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
            $table->string('thumbnail_url', 1000)->nullable()->after('seo_description');
            $table->string('canonical_url', 1000)->nullable()->after('thumbnail_url');
        });
    }

    public function down(): void
    {
        Schema::table('video_lessons', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'seo_description',
                'thumbnail_url',
                'canonical_url',
            ]);
        });

        Schema::table('content_items', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'seo_description',
                'canonical_url',
                'og_image_url',
                'expertise_level',
                'reviewed_by',
                'reviewed_at',
                'references',
            ]);
        });

        Schema::table('content_categories', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'seo_description',
                'canonical_url',
            ]);
        });
    }
};
