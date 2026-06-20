<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('name');
            $table->string('learning_format', 50)->default('hybrid')->after('level');
            $table->string('price_label')->nullable()->after('default_price_vnd');
            $table->string('thumbnail_url', 1000)->nullable()->after('price_label');
            $table->string('hero_image_url', 1000)->nullable()->after('thumbnail_url');
            $table->jsonb('who_should_join')->nullable()->after('hero_image_url');
            $table->jsonb('prerequisites')->nullable()->after('who_should_join');
            $table->jsonb('tools_covered')->nullable()->after('prerequisites');
            $table->string('primary_cta')->default('Đăng ký tư vấn')->after('tools_covered');
            $table->boolean('is_featured')->default(false)->after('primary_cta');
            $table->unsignedInteger('sort_order')->default(0)->after('is_featured');
            $table->string('seo_title')->nullable()->after('sort_order');
            $table->string('seo_description', 500)->nullable()->after('seo_title');
            $table->string('canonical_url', 1000)->nullable()->after('seo_description');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'subtitle',
                'learning_format',
                'price_label',
                'thumbnail_url',
                'hero_image_url',
                'who_should_join',
                'prerequisites',
                'tools_covered',
                'primary_cta',
                'is_featured',
                'sort_order',
                'seo_title',
                'seo_description',
                'canonical_url',
            ]);
        });
    }
};
