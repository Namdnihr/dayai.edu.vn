<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('temperature', 30)->default('warm')->after('priority');
            $table->string('pipeline_stage', 50)->default('new')->after('temperature');
            $table->string('lost_reason_type', 80)->nullable()->after('lost_reason');
            $table->integer('expected_value_vnd')->default(0)->after('interested_course_id');
            $table->timestamp('last_activity_at')->nullable()->after('next_follow_up_at');

            $table->index(['tenant_id', 'temperature', 'status']);
            $table->index(['tenant_id', 'pipeline_stage']);
            $table->index(['tenant_id', 'lost_reason_type']);
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'temperature', 'status']);
            $table->dropIndex(['tenant_id', 'pipeline_stage']);
            $table->dropIndex(['tenant_id', 'lost_reason_type']);

            $table->dropColumn([
                'temperature',
                'pipeline_stage',
                'lost_reason_type',
                'expected_value_vnd',
                'last_activity_at',
            ]);
        });
    }
};
