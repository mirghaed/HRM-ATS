<?php

namespace App\Listeners;

use App\Events\ApplicationCreated;
use App\Events\ApplicationStatusChanged;
use App\Models\HrmActivityLog;
use App\Services\HRM\HrmSettingService;

class NotifyDepartmentManager
{
    public function __construct(private readonly HrmSettingService $settingService)
    {
    }

    public function handle(object $event): void
    {
        if (! (bool) $this->settingService->get('notifications.department_manager_on_new_application', true)) {
            return;
        }

        $application = null;

        if ($event instanceof ApplicationCreated) {
            $application = $event->application;
        }

        if ($event instanceof ApplicationStatusChanged) {
            $application = $event->application;
        }

        if (! $application || ! $application->department?->manager_user_id) {
            return;
        }

        HrmActivityLog::create([
            'application_id' => $application->id,
            'candidate_id' => $application->candidate_id,
            'user_id' => $application->department->manager_user_id,
            'action' => 'notification.department_manager',
            'title' => 'اعلان به مدیر دپارتمان',
            'description' => 'اعلان داخلی برای بررسی رزومه ارسال شد.',
            'properties' => ['receiver_user_id' => $application->department->manager_user_id],
        ]);
    }
}
