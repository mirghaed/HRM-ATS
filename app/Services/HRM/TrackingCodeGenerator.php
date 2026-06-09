<?php

namespace App\Services\HRM;

use App\Models\Application;

class TrackingCodeGenerator
{
    public function generate(): string
    {
        $length = $this->lengthForVolume(Application::withTrashed()->count() + 1);

        for ($attempt = 0; $attempt < 30; $attempt++) {
            $code = $this->randomDigits($length);

            if (! Application::withTrashed()->where('tracking_code', $code)->exists()) {
                return $code;
            }
        }

        throw new \RuntimeException('Unable to generate a unique tracking code.');
    }

    private function lengthForVolume(int $nextCount): int
    {
        if ($nextCount <= 900) {
            return 3;
        }

        if ($nextCount <= 9_000) {
            return 4;
        }

        if ($nextCount <= 90_000) {
            return 5;
        }

        return 6;
    }

    private function randomDigits(int $length): string
    {
        $min = (int) pow(10, $length - 1);
        $max = (int) pow(10, $length) - 1;

        return (string) random_int($min, $max);
    }
}
