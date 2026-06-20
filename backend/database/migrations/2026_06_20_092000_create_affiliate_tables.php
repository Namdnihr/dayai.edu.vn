<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_partners', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('person_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code');
            $table->string('partner_type', 50)->default('individual');
            $table->unsignedTinyInteger('default_commission_percent')->default(10);
            $table->string('status', 30)->default('active');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('affiliate_links', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('affiliate_partner_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code');
            $table->string('campaign')->nullable();
            $table->string('target_url', 1000)->nullable();
            $table->unsignedTinyInteger('commission_percent')->nullable();
            $table->string('status', 30)->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['affiliate_partner_id', 'status']);
        });

        Schema::create('affiliate_clicks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('affiliate_partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('affiliate_link_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('affiliate_code')->nullable();
            $table->string('referral_code')->nullable();
            $table->string('click_id')->nullable();
            $table->string('landing_page', 1000)->nullable();
            $table->string('referrer_url', 1000)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('clicked_at');
            $table->timestamps();

            $table->index(['tenant_id', 'affiliate_code']);
            $table->index(['lead_id', 'clicked_at']);
        });

        Schema::create('affiliate_commissions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('affiliate_partner_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('affiliate_link_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('order_id')->constrained()->cascadeOnDelete();
            $table->string('affiliate_code')->nullable();
            $table->unsignedTinyInteger('commission_percent')->default(10);
            $table->unsignedBigInteger('order_total_vnd')->default(0);
            $table->unsignedBigInteger('commission_vnd')->default(0);
            $table->string('status', 30)->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('order_id');
            $table->index(['tenant_id', 'status']);
            $table->index(['affiliate_partner_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_commissions');
        Schema::dropIfExists('affiliate_clicks');
        Schema::dropIfExists('affiliate_links');
        Schema::dropIfExists('affiliate_partners');
    }
};
