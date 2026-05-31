<?php

namespace App\Services\Sms;

use App\Services\HRM\HrmSettingService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class SmsIrClient
{
    public function __construct(private readonly HrmSettingService $settingService)
    {
    }

    public function sendPattern(string $mobile, string $templateId, array $parameters = []): array
    {
        $baseUrl = rtrim((string) $this->settingService->get('sms.base_url', config('services.smsir.base_url', 'https://api.sms.ir/v1')), '/');
        $apiKey = (string) $this->settingService->get('sms.api_key', config('services.smsir.api_key'));

        if ($apiKey === '') {
            return ['success' => false, 'error' => 'SMS API key is missing.'];
        }

        try {
            $response = Http::timeout(15)
                ->withHeader('X-API-KEY', $apiKey)
                ->post($baseUrl.'/send/verify', [
                    'mobile' => $mobile,
                    'templateId' => $templateId,
                    'parameters' => collect($parameters)
                        ->map(fn ($value, $key) => ['name' => $key, 'value' => (string) $value])
                        ->values()
                        ->all(),
                ]);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
                'body' => $response->body(),
            ];
        } catch (ConnectionException $exception) {
            return [
                'success' => false,
                'error' => $exception->getMessage(),
            ];
        }
    }
}
