<?php

namespace App\Providers;

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
use App\Listeners\CreateHrmActivityLog;
use App\Listeners\NotifyDepartmentManager;
use App\Listeners\NotifyInterviewer;
use App\Listeners\QueueInterviewReminder;
use App\Listeners\RecalculateScores;
use App\Listeners\SendStatusSmsIfNeeded;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ApplicationCreated::class => [
            CreateHrmActivityLog::class,
            NotifyDepartmentManager::class,
            RecalculateScores::class,
        ],
        ApplicationStatusChanged::class => [
            CreateHrmActivityLog::class,
            SendStatusSmsIfNeeded::class,
            NotifyDepartmentManager::class,
            RecalculateScores::class,
        ],
        InterviewScheduled::class => [
            CreateHrmActivityLog::class,
            NotifyInterviewer::class,
            QueueInterviewReminder::class,
        ],
        InterviewRescheduled::class => [
            CreateHrmActivityLog::class,
            NotifyInterviewer::class,
            QueueInterviewReminder::class,
        ],
        InterviewCancelled::class => [
            CreateHrmActivityLog::class,
        ],
        CandidateHired::class => [CreateHrmActivityLog::class],
        CandidateRejected::class => [CreateHrmActivityLog::class],
        SmsQueued::class => [CreateHrmActivityLog::class],
        SmsSent::class => [CreateHrmActivityLog::class],
        SmsFailed::class => [CreateHrmActivityLog::class],
    ];
}
