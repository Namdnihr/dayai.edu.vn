<?php

namespace Tests\Feature;

use App\Models\AutomationLog;
use App\Models\Branch;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Tenant;
use App\Services\AutomationWorkflowRunner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutomationWorkflowRunnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_runner_sends_lead_confirmation_and_skips_duplicate_within_cooldown(): void
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

        AutomationWorkflowRunner::seedDefaultWorkflows($tenant);

        Lead::query()->create([
            'tenant_id' => $tenant->id,
            'branch_id' => $branch->id,
            'lead_type' => 'student',
            'status' => 'new',
            'full_name' => 'Lead Automation',
            'phone' => '0901555000',
            'course_slug' => 'ai-can-ban',
        ]);

        $runner = app(AutomationWorkflowRunner::class);
        $firstRun = $runner->run('lead.created');

        $this->assertSame(1, $firstRun['workflow_count']);
        $this->assertSame(1, $firstRun['sent_count']);
        $this->assertSame(1, Notification::query()->count());
        $this->assertSame(1, AutomationLog::query()->where('status', 'sent')->count());

        $secondRun = $runner->run('lead.created');

        $this->assertSame(1, $secondRun['skipped_count']);
        $this->assertSame(1, Notification::query()->count());
    }

    public function test_console_command_runs_automation(): void
    {
        $tenant = Tenant::query()->create([
            'name' => 'DAYAI',
            'code' => 'dayai',
            'status' => 'active',
        ]);

        AutomationWorkflowRunner::seedDefaultWorkflows($tenant);

        $this->artisan('automation:run', ['--trigger' => 'lead.created'])
            ->expectsOutputToContain('Automation done')
            ->assertSuccessful();
    }
}
