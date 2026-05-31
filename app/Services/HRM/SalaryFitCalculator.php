<?php

namespace App\Services\HRM;

class SalaryFitCalculator
{
    public function calculate(?int $expectedMin, ?int $expectedMax, ?int $jobMin, ?int $jobMax): ?int
    {
        if (! $expectedMin || ! $jobMin || ! $jobMax) {
            return null;
        }

        $candidateMid = (int) round(($expectedMin + ($expectedMax ?: $expectedMin)) / 2);
        $jobMid = (int) round(($jobMin + $jobMax) / 2);

        if ($jobMid === 0) {
            return null;
        }

        $diffPercent = abs($candidateMid - $jobMid) / $jobMid;

        $score = max(0, 100 - ((int) round($diffPercent * 100)));

        return min(100, $score);
    }
}