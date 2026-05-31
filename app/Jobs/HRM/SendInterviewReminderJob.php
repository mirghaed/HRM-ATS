<?php

namespace App\Jobs\HRM;

use App\Models\InterviewReminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendInterviewReminderJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $reminderId)
    {
    }

    public function handle(): void
    {
        $reminder = InterviewReminder::query()->with('interview.application.currentStatus')->find($this->reminderId);

        if (! $reminder || $reminder->status !== 'pending') {
            return;
        }

        if (! $reminder->interview || $reminder->interview->status !== 'scheduled') {
            $reminder->update(['status' => 'skipped', 'error_message' => 'Interview is not scheduled anymore.']);
            return;
        }

        if (! $reminder->interview?->application?->currentStatus?->sms_template_id) {
            $reminder->update(['status' => 'skipped', 'error_message' => 'No status sms template assigned.']);
            return;
        }

        SendSmsJob::dispatch(
            applicationId: $reminder->interview->application_id,
            templateId: $reminder->interview->application->currentStatus->sms_template_id,
            triggeredByUserId: $reminder->interview->scheduled_by,
            variables: [
                'interview_time' => $reminder->interview->start_at->format('Y-m-d H:i'),
            ],
        );

        $reminder->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }
}
