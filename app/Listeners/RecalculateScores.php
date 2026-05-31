<?php

namespace App\Listeners;

use App\Events\ApplicationCreated;
use App\Events\ApplicationStatusChanged;
use App\Jobs\HRM\RecalculateApplicationScoresJob;

class RecalculateScores
{
    public function handle(object $event): void
    {
        $applicationId = null;

        if ($event instanceof ApplicationCreated) {
            $applicationId = $event->application->id;
        }

        if ($event instanceof ApplicationStatusChanged) {
            $applicationId = $event->application->id;
        }

        if ($applicationId) {
            RecalculateApplicationScoresJob::dispatch($applicationId);
        }
    }
}