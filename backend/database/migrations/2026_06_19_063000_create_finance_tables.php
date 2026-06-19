<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('customer_account_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_code', 80);
            $table->string('order_type', 50)->default('b2c');
            $table->string('status', 50)->default('draft');
            $table->timestamp('ordered_at')->nullable();
            $table->bigInteger('subtotal_vnd')->default(0);
            $table->bigInteger('discount_vnd')->default(0);
            $table->bigInteger('total_vnd')->default(0);
            $table->bigInteger('paid_vnd')->default(0);
            $table->bigInteger('balance_vnd')->default(0);
            $table->text('notes')->nullable();
            $table->foreignUlid('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'order_code']);
            $table->index(['tenant_id', 'status', 'order_type']);
            $table->index('customer_account_id');
            $table->index('lead_id');
            $table->index('ordered_at');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('class_group_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUlid('student_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->bigInteger('unit_price_vnd')->default(0);
            $table->bigInteger('discount_vnd')->default(0);
            $table->bigInteger('line_total_vnd')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('order_id');
            $table->index('course_id');
            $table->index('student_profile_id');
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('customer_account_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_code', 80);
            $table->string('status', 50)->default('issued');
            $table->timestamp('issued_at')->nullable();
            $table->date('due_date')->nullable();
            $table->bigInteger('amount_vnd')->default(0);
            $table->bigInteger('paid_vnd')->default(0);
            $table->bigInteger('balance_vnd')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'invoice_code']);
            $table->index(['tenant_id', 'status']);
            $table->index('order_id');
            $table->index('customer_account_id');
            $table->index('due_date');
        });

        Schema::create('receivables', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('customer_account_id')->constrained()->cascadeOnDelete();
            $table->string('status', 50)->default('open');
            $table->bigInteger('original_amount_vnd')->default(0);
            $table->bigInteger('paid_vnd')->default(0);
            $table->bigInteger('balance_vnd')->default(0);
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status', 'due_date']);
            $table->index('customer_account_id');
            $table->index('invoice_id');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('order_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('customer_account_id')->constrained()->cascadeOnDelete();
            $table->string('payment_code', 80);
            $table->string('payment_method', 50)->default('bank_transfer');
            $table->string('status', 50)->default('completed');
            $table->bigInteger('amount_vnd')->default(0);
            $table->timestamp('paid_at');
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->foreignUlid('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'payment_code']);
            $table->index(['tenant_id', 'status', 'paid_at']);
            $table->index('invoice_id');
            $table->index('order_id');
            $table->index('customer_account_id');
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('payment_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_code', 80);
            $table->timestamp('issued_at');
            $table->foreignUlid('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payer_name');
            $table->bigInteger('amount_vnd')->default(0);
            $table->text('content')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('payment_id');
            $table->unique(['tenant_id', 'receipt_code']);
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('receivables');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
