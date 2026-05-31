<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class HrmCleanupTempFiles extends Command
{
    protected $signature = 'hrm:cleanup-temp-files';

    protected $description = 'Cleanup stale HRM temporary import files';

    public function handle(): int
    {
        $deleted = 0;

        foreach (Storage::disk('local')->files('temp/hrm-imports') as $file) {
            $lastModified = Carbon::createFromTimestamp(Storage::disk('local')->lastModified($file));

            if ($lastModified->diffInHours(now()) > 24) {
                Storage::disk('local')->delete($file);
                $deleted++;
            }
        }

        $this->info("Deleted {$deleted} temp files.");

        return self::SUCCESS;
    }
}
