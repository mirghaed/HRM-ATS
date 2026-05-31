<?php

namespace App\Services\HRM;

use App\Models\HrmSetting;
use Illuminate\Support\Facades\Cache;

class HrmSettingService
{
    private const CACHE_KEY = 'hrm.settings.map';

    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return $settings[$key] ?? $default;
    }

    public function set(string $key, mixed $value, string $group = 'general', string $type = 'string', bool $isPublic = false): HrmSetting
    {
        $serialized = $type === 'json' ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value;

        $setting = HrmSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $serialized,
                'group' => $group,
                'type' => $type,
                'is_public' => $isPublic,
            ]
        );

        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(30), function () {
            return HrmSetting::query()
                ->get()
                ->mapWithKeys(function (HrmSetting $setting) {
                    return [$setting->key => $this->castValue($setting->value, $setting->type)];
                })
                ->all();
        });
    }

    private function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
