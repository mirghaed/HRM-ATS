<?php

namespace App\Jobs\HRM;

use App\Models\CandidateFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ParseResumeFileJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $candidateFileId)
    {
    }

    public function handle(): void
    {
        $file = CandidateFile::query()->find($this->candidateFileId);

        if (! $file) {
            return;
        }

        // Placeholder parser: in production replace with a dedicated CV parser service.
        $file->update([
            'parsed_text' => null,
            'parsed_json' => [
                'parsed_at' => now()->toDateTimeString(),
                'status' => 'queued_placeholder',
            ],
        ]);
    }
}