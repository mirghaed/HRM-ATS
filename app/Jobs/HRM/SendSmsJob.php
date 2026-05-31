<?php

namespace App\Jobs\HRM;

use App\Events\SmsFailed;
use App\Events\SmsQueued;
use App\Events\SmsSent;
use App\Models\Application;
use App\Models\SmsLog;
use App\Models\SmsTemplate;
use App\Services\Sms\SmsIrClient;
use App\Services\Sms\SmsTemplateRenderer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $applicationId,
        public int $templateId,
        public ?int $triggeredByUserId = null,
        public array $variables = [],
    ) {
    }

    public function handle(SmsIrClient $smsClient, SmsTemplateRenderer $renderer): void
    {
        $application = Application::query()->with('candidate', 'jobPosition')->find($this->applicationId);
        $template = SmsTemplate::query()->find($this->templateId);

        if (! $application || ! $template || ! $application->candidate?->mobile) {
            return;
        }

        $variables = array_merge([
            'candidate_name' => $application->candidate->full_name,
            'job_title' => $application->jobPosition?->title,
            'date' => now()->toDateString(),
            'time' => now()->format('H:i'),
        ], $this->variables);

        $message = $renderer->render((string) $template->body_preview, $variables);

        $smsLog = SmsLog::create([
            'candidate_id' => $application->candidate_id,
            'application_id' => $application->id,
            'sms_template_id' => $template->id,
            'mobile' => $application->candidate->mobile,
            'provider' => 'smsir',
            'provider_template_id' => $template->provider_template_id,
            'message_text' => $message,
            'parameters' => $variables,
            'status' => 'pending',
            'sent_by_user_id' => $this->triggeredByUserId,
        ]);

        event(new SmsQueued($smsLog));

        $response = $smsClient->sendPattern(
            mobile: $application->candidate->mobile,
            templateId: (string) $template->provider_template_id,
            parameters: $variables,
        );

        if (($response['success'] ?? false) === true) {
            $smsLog->update([
                'status' => 'sent',
                'provider_response' => $response,
                'sent_at' => now(),
            ]);
            event(new SmsSent($smsLog->fresh()));
            return;
        }

        $smsLog->update([
            'status' => 'failed',
            'provider_response' => $response,
            'failed_at' => now(),
        ]);

        event(new SmsFailed($smsLog->fresh(), (string) ($response['error'] ?? 'unknown')));
    }
}