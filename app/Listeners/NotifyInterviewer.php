<?php

namespace App\Listeners;

use App\Events\InterviewRescheduled;
use App\Events\InterviewScheduled;
use App\Models\HrmActivityLog;
use App\Services\HRM\HrmSettingService;

class NotifyInterviewer
{
    public function __construct(private readonly HrmSettingService $settingService)
    {
    }

    public function handle(object $event): void
    {
        if (! $event instanceof InterviewScheduled && ! $event instanceof InterviewRescheduled) {
            return;
        }

        if (! (bool) $this->settingService->get('notifications.interviewer_on_schedule', true)) {
            return;
        }

        $title = $event instanceof InterviewRescheduled ? 'اعلان به مصاحبه‌گر (زمان جدید)' : 'اعلان به مصاحبه‌گر';
        $description = $event instanceof InterviewRescheduled
            ? 'زمان مصاحبه بروزرسانی شد و اعلان جدید ارسال شد.'
            : 'مصاحبه جدید برای مصاحبه‌گر ثبت شد.';

        HrmActivityLog::create([
            'application_id' => $event->interview->application_id,
            'candidate_id' => $event->interview->candidate_id,
            'user_id' => $event->interview->interviewer_id,
            'action' => 'notification.interviewer',
            'title' => $title,
            'description' => $description,
            'properties' => ['interview_id' => $event->interview->id],
        ]);
    }
}
