<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('short_name', 100)->nullable();
            $table->string('organization_type', 50)->default('company')->index();
            $table->string('tax_code', 50)->nullable()->index();
            $table->string('industry', 100)->nullable();
            $table->string('company_size', 50)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('address')->nullable();
            $table->string('status', 30)->default('prospect')->index();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->foreignUlid('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUlid('updated_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'organization_type', 'status']);
        });

        Schema::create('organization_contacts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('person_id')->constrained()->cascadeOnDelete();
            $table->string('contact_role', 50)->default('other')->index();
            $table->string('job_title')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->string('status', 30)->default('active')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'person_id', 'contact_role']);
            $table->index(['tenant_id', 'contact_role', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_contacts');
        Schema::dropIfExists('organizations');
    }
};

