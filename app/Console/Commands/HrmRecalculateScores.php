<?php

namespace App\Console\Commands;

use App\Jobs\HRM\RecalculateApplicationScoresJob;
use Illuminate\Console\Command;

class HrmRecalculateScores extends Command
{
    protected $signature = 'hrm:recalculate-scores';

    protected $description = 'Recalculate score fields for HRM applications';

    public function handle(): int
    {
        RecalculateApplicationScoresJob::dispatch();

        $this->info('Score recalculation job dispatched.');

        return self::SUCCESS;
    }
}