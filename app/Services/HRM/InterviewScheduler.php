<?php

namespace App\Services\HRM;

use App\Events\InterviewScheduled;
use App\Models\Application;
use App\Models\Interview;

class InterviewScheduler
{
    public function schedule(Application $application, array $payload): Interview
    {
        $interview = Interview::create([
            'application_id' => $application->id,
            'candidate_id' => $application->candidate_id,
            'job_position_id' => $application->job_position_id,
            'department_id' => $application->department_id,
            'interviewer_id' => $payload['interviewer_id'],
            'scheduled_by' => auth()->id(),
            'type' => $payload['type'],
            'status' => 'scheduled',
            'start_at' => $payload['start_at'],
            'end_at' => $payload['end_at'] ?? null,
            'location_title' => $payload['location_title'] ?? null,
            'address' => $payload['address'] ?? null,
            'online_meeting_url' => $payload['online_meeting_url'] ?? null,
            'description' => $payload['description'] ?? null,
            'send_sms_to_candidate' => (bool) ($payload['send_sms_to_candidate'] ?? false),
        ]);

        event(new InterviewScheduled($interview));

        return $interview;
    }
}