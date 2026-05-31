<?php

namespace App\Listeners;

use App\Events\InterviewRescheduled;
use App\Events\InterviewScheduled;
use App\Jobs\HRM\SendSmsJob;
use App\Jobs\HRM\SendInterviewReminderJob;
use App\Models\InterviewReminder;
use App\Services\HRM\HrmSettingService;

class QueueInterviewReminder
{
    public function __construct(private readonly HrmSettingService $settingService)
    {
    }

    public function handle(object $event): void
    {
        if (! $event instanceof InterviewScheduled && ! $event instanceof InterviewRescheduled) {
            return;
        }

        if (! (bool) $this->settingService->get('sms.enabled', false)) {
            return;
        }

        if (! $event->interview->send_sms_to_candidate) {
            return;
        }

        $templateId = $event->interview->application?->currentStatus?->sms_template_id;
        if ($templateId) {
            SendSmsJob::dispatch(
                applicationId: $event->interview->application_id,
                templateId: (int) $templateId,
                triggeredByUserId: $event->interview->scheduled_by,
                variables: [
                    'interview_date' => $event->interview->start_at->format('Y-m-d'),
                    'interview_time' => $event->interview->start_at->format('H:i'),
                    'interview_address' => (string) ($event->interview->address ?: $event->interview->location_title ?: $event->interview->online_meeting_url),
                ],
            );
        }

        $sendAt = $event->interview->start_at->copy()->subHour();

        if ($sendAt->isPast()) {
            $sendAt = now()->addMinute();
        }

        InterviewReminder::query()
            ->where('interview_id', $event->interview->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'skipped',
                'error_message' => 'Superseded by a newer schedule.',
                'sent_at' => now(),
            ]);

        $reminder = InterviewReminder::create([
            'interview_id' => $event->interview->id,
            'type' => 'sms',
            'send_at' => $sendAt,
            'status' => 'pending',
        ]);

        SendInterviewReminderJob::dispatch($reminder->id)->delay($sendAt);
    }
}
