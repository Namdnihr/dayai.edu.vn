<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('person_id')->constrained('people')->cascadeOnDelete();
            $table->string('student_code', 50);
            $table->string('student_type', 50);
            $table->string('current_school')->nullable();
            $table->string('current_company')->nullable();
            $table->string('job_title')->nullable();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->text('learning_goal')->nullable();
            $table->string('entry_level', 50)->nullable();
            $table->string('status', 50)->default('lead_converted');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'student_code']);
            $table->unique(['tenant_id', 'person_id']);
            $table->index(['tenant_id', 'student_type', 'status']);
            $table->index('organization_id');
        });

        Schema::create('guardian_relations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('guardian_person_id')->constrained('people')->cascadeOnDelete();
            $table->foreignUlid('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('relation_type', 50)->default('guardian');
            $table->boolean('is_primary')->default(true);
            $table->boolean('can_view_finance')->default(true);
            $table->boolean('can_view_progress')->default(true);
            $table->boolean('can_receive_notifications')->default(true);
            $table->timestamps();

            $table->unique(['guardian_person_id', 'student_profile_id']);
            $table->index(['tenant_id', 'student_profile_id']);
        });

        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_code', 50);
            $table->string('account_type', 50);
            $table->foreignUlid('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('display_name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('billing_address')->nullable();
            $table->string('tax_code', 50)->nullable();
            $table->string('status', 50)->default('active');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'customer_code']);
            $table->index(['tenant_id', 'account_type', 'status']);
            $table->index('person_id');
            $table->index('organization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_accounts');
        Schema::dropIfExists('guardian_relations');
        Schema::dropIfExists('student_profiles');
    }
};
