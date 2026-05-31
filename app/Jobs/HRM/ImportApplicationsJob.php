<?php

namespace App\Jobs\HRM;

use App\Models\ImportRun;
use App\Services\HRM\ApplicationCreator;
use App\Services\HRM\ResumeImportManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ImportApplicationsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $importRunId, public string $path)
    {
    }

    public function handle(ApplicationCreator $creator, ResumeImportManager $manager): void
    {
        $run = ImportRun::query()->find($this->importRunId);
        if (! $run) {
            return;
        }

        $run->update(['status' => 'running', 'started_at' => now(), 'error_message' => null]);

        $fullPath = Storage::disk('local')->path($this->path);
        if (! file_exists($fullPath)) {
            $run->update(['status' => 'failed', 'error_message' => 'Import file not found.', 'finished_at' => now()]);
            return;
        }

        try {
            $manager->processRun($run, $fullPath, $creator);

            $run->update([
                'status' => $run->fresh()->failed_items > 0 ? 'partial' : 'completed',
                'finished_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $run->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'finished_at' => now(),
            ]);
        }
    }
}
