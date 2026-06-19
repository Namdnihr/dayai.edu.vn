<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('content_categories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('category_type', 50)->default('knowledge');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'category_type', 'is_active']);
        });

        Schema::create('content_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('content_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('content_type', 50)->default('article');
            $table->string('status', 50)->default('draft');
            $table->string('excerpt', 500)->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_image_url', 1000)->nullable();
            $table->jsonb('tags')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'content_type', 'status']);
            $table->index('published_at');
        });

        Schema::create('video_lessons', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('content_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->string('status', 50)->default('draft');
            $table->string('video_provider', 50)->default('youtube');
            $table->string('video_url', 1000)->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->string('access_level', 50)->default('public');
            $table->text('summary')->nullable();
            $table->jsonb('resources')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'status', 'access_level']);
            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_lessons');
        Schema::dropIfExists('content_items');
        Schema::dropIfExists('content_categories');
    }
};
