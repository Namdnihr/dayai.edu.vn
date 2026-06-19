<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('person_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('student_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('audience_type')->default('student');
            $table->string('notification_type')->default('general');
            $table->string('channel')->default('portal');
            $table->string('title');
            $table->text('body');
            $table->string('status')->default('published');
            $table->string('priority')->default('normal');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'audience_type']);
            $table->index(['tenant_id', 'status']);
            $table->index(['student_profile_id', 'published_at']);
            $table->index(['organization_id', 'published_at']);
            $table->index(['person_id', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
