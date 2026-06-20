<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_auth_tokens', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('person_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone', 30);
            $table->string('student_code', 50);
            $table->string('code_hash');
            $table->string('access_token_hash')->nullable();
            $table->string('channel', 30)->default('demo');
            $table->unsignedTinyInteger('attempt_count')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['student_profile_id', 'expires_at']);
            $table->index(['tenant_id', 'phone', 'student_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_auth_tokens');
    }
};
