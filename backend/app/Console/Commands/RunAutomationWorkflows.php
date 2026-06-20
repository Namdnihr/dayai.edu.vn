<?php

namespace App\Console\Commands;

use App\Services\AutomationWorkflowRunner;
use Illuminate\Console\Command;

class RunAutomationWorkflows extends Command
{
    protected $signature = 'automation:run {--trigger= : Chỉ chạy một trigger cụ thể}';

    protected $description = 'Run active automation workflows and write notification/log outbox records.';

    public function handle(AutomationWorkflowRunner $runner): int
    {
        $summary = $runner->run($this->option('trigger') ?: null);

        $this->info(sprintf(
            'Automation done: workflows=%d processed=%d sent=%d skipped=%d failed=%d',
            $summary['workflow_count'],
            $summary['processed_count'],
            $summary['sent_count'],
            $summary['skipped_count'],
            $summary['failed_count'],
        ));

        return self::SUCCESS;
    }
}
