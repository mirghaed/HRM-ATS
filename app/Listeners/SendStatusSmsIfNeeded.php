<?php

namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Jobs\HRM\SendSmsJob;
use App\Services\HRM\HrmSettingService;

class SendStatusSmsIfNeeded
{
    public function __construct(private readonly HrmSettingService $settingService)
    {
    }

    public function handle(ApplicationStatusChanged $event): void
    {
        if (! (bool) $this->settingService->get('sms.enabled', false)) {
            return;
        }

        $status = $event->application->currentStatus;

        if (! $status || ! $status->notify_candidate || ! $status->sms_template_id) {
            return;
        }

        SendSmsJob::dispatch(
            applicationId: $event->application->id,
            templateId: $status->sms_template_id,
            triggeredByUserId: auth()->id(),
        );
    }
}
