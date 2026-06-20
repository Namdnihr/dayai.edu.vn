<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('utm_content')->nullable()->after('utm_campaign');
            $table->string('utm_term')->nullable()->after('utm_content');
            $table->string('page_url', 1000)->nullable()->after('utm_term');
            $table->string('landing_page', 1000)->nullable()->after('page_url');
            $table->string('referrer_url', 1000)->nullable()->after('landing_page');
            $table->string('course_slug')->nullable()->after('referrer_url');
            $table->string('affiliate_code')->nullable()->after('course_slug');
            $table->string('referral_code')->nullable()->after('affiliate_code');
            $table->string('click_id')->nullable()->after('referral_code');
            $table->string('first_touch_source')->nullable()->after('click_id');
            $table->string('last_touch_source')->nullable()->after('first_touch_source');

            $table->index(['tenant_id', 'utm_source', 'utm_campaign']);
            $table->index(['tenant_id', 'affiliate_code']);
            $table->index(['tenant_id', 'course_slug']);
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'utm_source', 'utm_campaign']);
            $table->dropIndex(['tenant_id', 'affiliate_code']);
            $table->dropIndex(['tenant_id', 'course_slug']);

            $table->dropColumn([
                'utm_content',
                'utm_term',
                'page_url',
                'landing_page',
                'referrer_url',
                'course_slug',
                'affiliate_code',
                'referral_code',
                'click_id',
                'first_touch_source',
                'last_touch_source',
            ]);
        });
    }
};
