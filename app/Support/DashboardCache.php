<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class DashboardCache
{
    public const RANGES = ['today', '7d', '30d'];

    public static function forget(): void
    {
        foreach (self::RANGES as $range) {
            Cache::forget(self::key($range));
        }
    }

    public static function key(string $range): string
    {
        return "hrm.dashboard.metrics.{$range}";
    }
}
