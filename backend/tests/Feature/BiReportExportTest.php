<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Course;
use App\Models\CustomerAccount;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Person;
use App\Models\Tenant;
use App\Services\BiReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BiReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_bi_report_service_returns_admissions_and_revenue_rows(): void
    {
        $this->seedReportData();

        $service = app(BiReportService::class);

        $this->assertSame(1, $service->admissionsBySource()->count());
        $this->assertSame(1, $service->revenueByCourse()->count());
        $this->assertStringContainsString('source_name', $service->toCsv($service->admissionsBySource()));
    }

    public function test_csv_export_endpoint_downloads_report(): void
    {
        $this->seedReportData();

        $response = $this->get('/api/reports/revenue-by-course.csv');

        $response
            ->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

        $this->assertStringContainsString('course_name', $response->getContent());
        $this->assertStringContainsString('AI Cơ bản', $response->getContent());
    }

    private function seedReportData(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        $branch = Branch::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Cơ sở chính',
            'code' => 'main',
            'status' => 'active',
        ]);

        $source = LeadSource::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Website',
            'code' => 'website',
            'source_type' => 'website',
            'is_active' => true,
        ]);

        Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'lead_source_id' => $source->id,
            'lead_type' => 'student',
            'status' => 'registered',
            'full_name' => 'Lead BI',
            'phone' => '0901999000',
            'utm_campaign' => 'k01',
            'expected_value_vnd' => 3000000,
            'converted_at' => now(),
        ]);

        $course = Course::query()->create([
            'tenant_id' => $tenant->id,
            'name' => 'AI Cơ bản',
            'slug' => 'ai-co-ban',
            'course_code' => 'AI-BI',
            'status' => 'published',
        ]);

        $person = Person::query()->create([
            'tenant_id' => $tenant->id,
            'full_name' => 'Buyer BI',
            'phone' => '0901999000',
        ]);

        $customer = CustomerAccount::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'person_id' => $person->id,
            'customer_code' => 'KH-BI',
            'account_type' => 'individual',
            'display_name' => 'Buyer BI',
            'phone' => '0901999000',
            'status' => 'active',
        ]);

        $order = Order::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'customer_account_id' => $customer->id,
            'order_code' => 'ORD-BI',
            'order_type' => 'b2c',
            'status' => 'partially_paid',
            'ordered_at' => now(),
            'total_vnd' => 3000000,
            'paid_vnd' => 1000000,
            'balance_vnd' => 2000000,
        ]);

        OrderItem::query()->create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'course_id' => $course->id,
            'description' => 'AI Cơ bản',
            'quantity' => 1,
            'unit_price_vnd' => 3000000,
            'line_total_vnd' => 3000000,
        ]);

        $invoice = Invoice::query()->create([
            'tenant_id' => $tenant->id,
            'order_id' => $order->id,
            'customer_account_id' => $customer->id,
            'invoice_code' => 'INV-BI',
            'status' => 'issued',
            'issued_at' => now(),
            'due_date' => now()->addDays(7)->toDateString(),
            'amount_vnd' => 3000000,
            'paid_vnd' => 1000000,
            'balance_vnd' => 2000000,
        ]);

        Payment::query()->create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'order_id' => $order->id,
            'customer_account_id' => $customer->id,
            'payment_code' => 'PAY-BI',
            'payment_method' => 'bank_transfer',
            'status' => 'completed',
            'amount_vnd' => 1000000,
            'paid_at' => now(),
        ]);
    }
}
