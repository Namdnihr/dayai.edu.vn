<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('class_group_id')->nullable()->constrained()->nullOnDelete();
            $table->string('certificate_code');
            $table->string('verification_token')->unique();
            $table->string('title');
            $table->string('status')->default('draft');
            $table->decimal('final_score', 8, 2)->nullable();
            $table->string('grade')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('file_path', 500)->nullable();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'certificate_code']);
            $table->index(['student_profile_id', 'issued_at']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
