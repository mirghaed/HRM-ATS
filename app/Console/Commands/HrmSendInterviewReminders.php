<?php

namespace App\Console\Commands;

use App\Jobs\HRM\SendInterviewReminderJob;
use App\Models\InterviewReminder;
use Illuminate\Console\Command;

class HrmSendInterviewReminders extends Command
{
    protected $signature = 'hrm:send-interview-reminders';

    protected $description = 'Queue pending interview reminders that are due';

    public function handle(): int
    {
        $count = 0;

        InterviewReminder::query()
            ->where('status', 'pending')
            ->where('send_at', '<=', now())
            ->chunkById(100, function ($reminders) use (&$count) {
                foreach ($reminders as $reminder) {
                    SendInterviewReminderJob::dispatch($reminder->id);
                    $count++;
                }
            });

        $this->info("Queued {$count} interview reminders.");

        return self::SUCCESS;
    }
}