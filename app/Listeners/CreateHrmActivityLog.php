<?php

namespace App\Listeners;

use App\Events\ApplicationCreated;
use App\Events\ApplicationStatusChanged;
use App\Events\CandidateHired;
use App\Events\CandidateRejected;
use App\Events\InterviewCancelled;
use App\Events\InterviewRescheduled;
use App\Events\InterviewScheduled;
use App\Events\SmsFailed;
use App\Events\SmsQueued;
use App\Events\SmsSent;
use App\Models\HrmActivityLog;

class CreateHrmActivityLog
{
    public function handle(object $event): void
    {
        if ($event instanceof ApplicationCreated) {
            $this->log(
                applicationId: $event->application->id,
                candidateId: $event->application->candidate_id,
                action: 'application.created',
                title: 'رزومه جدید ثبت شد',
                description: 'درخواست استخدام جدید برای موقعیت ثبت شد.',
                properties: ['status_id' => $event->application->current_status_id],
            );
            return;
        }

        if ($event instanceof ApplicationStatusChanged) {
            $this->log(
                applicationId: $event->application->id,
                candidateId: $event->application->candidate_id,
                action: 'application.status_changed',
                title: 'وضعیت رزومه تغییر کرد',
                description: $event->history->note,
                properties: [
                    'from_status_id' => $event->history->from_status_id,
                    'to_status_id' => $event->history->to_status_id,
                ],
            );
            return;
        }

        if ($event instanceof InterviewScheduled) {
            $this->log(
                applicationId: $event->interview->application_id,
                candidateId: $event->interview->candidate_id,
                action: 'interview.scheduled',
                title: 'مصاحبه زمان‌بندی شد',
                description: 'زمان مصاحبه برای کارجو ثبت شد.',
                properties: ['interview_id' => $event->interview->id],
            );
            return;
        }

        if ($event instanceof InterviewRescheduled) {
            $this->log(
                applicationId: $event->interview->application_id,
                candidateId: $event->interview->candidate_id,
                action: 'interview.rescheduled',
                title: 'زمان مصاحبه تغییر کرد',
                description: 'مصاحبه برای این کارجو زمان‌بندی مجدد شد.',
                properties: ['interview_id' => $event->interview->id],
            );
            return;
        }

        if ($event instanceof InterviewCancelled) {
            $this->log(
                applicationId: $event->interview->application_id,
                candidateId: $event->interview->candidate_id,
                action: 'interview.cancelled',
                title: 'مصاحبه لغو شد',
                description: 'مصاحبه ثبت‌شده برای این کارجو لغو شد.',
                properties: ['interview_id' => $event->interview->id],
            );
            return;
        }

        if ($event instanceof CandidateHired) {
            $this->log(
                applicationId: $event->application->id,
                candidateId: $event->application->candidate_id,
                action: 'candidate.hired',
                title: 'کارجو استخدام شد',
                description: 'کارجو به مرحله استخدام رسید.',
            );
            return;
        }

        if ($event instanceof CandidateRejected) {
            $this->log(
                applicationId: $event->application->id,
                candidateId: $event->application->candidate_id,
                action: 'candidate.rejected',
                title: 'کارجو رد شد',
                description: 'فرایند جذب برای این کارجو متوقف شد.',
            );
            return;
        }

        if ($event instanceof SmsQueued) {
            $this->log(
                applicationId: $event->smsLog->application_id,
                candidateId: $event->smsLog->candidate_id,
                action: 'sms.queued',
                title: 'پیامک در صف قرار گرفت',
                description: null,
                properties: ['sms_log_id' => $event->smsLog->id],
            );
            return;
        }

        if ($event instanceof SmsSent) {
            $this->log(
                applicationId: $event->smsLog->application_id,
                candidateId: $event->smsLog->candidate_id,
                action: 'sms.sent',
                title: 'پیامک ارسال شد',
                description: null,
                properties: ['sms_log_id' => $event->smsLog->id],
            );
            return;
        }

        if ($event instanceof SmsFailed) {
            $this->log(
                applicationId: $event->smsLog->application_id,
                candidateId: $event->smsLog->candidate_id,
                action: 'sms.failed',
                title: 'ارسال پیامک ناموفق بود',
                description: $event->error,
                properties: ['sms_log_id' => $event->smsLog->id],
            );
        }
    }

    private function log(
        ?int $applicationId,
        ?int $candidateId,
        string $action,
        string $title,
        ?string $description = null,
        array $properties = [],
    ): void {
        HrmActivityLog::create([
            'application_id' => $applicationId,
            'candidate_id' => $candidateId,
            'user_id' => auth()->id(),
            'action' => $action,
            'title' => $title,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
