<?php

namespace App\Services\HRM;

use App\Models\JobPosition;
use Illuminate\Http\Request;

class JobPositionVisitRecorder
{
    public const TAG = 'public_page';

    public function record(JobPosition $jobPosition, Request $request): void
    {
        if ($this->shouldIgnore($request)) {
            return;
        }

        \visits($jobPosition, self::TAG)
            ->seconds(86400)
            ->increment();
    }

    public function shouldIgnore(Request $request): bool
    {
        if (auth()->check()) {
            return true;
        }

        $userAgent = strtolower((string) $request->userAgent());

        $botKeywords = [
            'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit',
            'telegrambot', 'whatsapp', 'preview', 'monitoring', 'uptime',
        ];

        foreach ($botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        return false;
    }

    public function stats(JobPosition $jobPosition): array
    {
        $counter = \visits($jobPosition, self::TAG);
        $viewsTotal = (int) $counter->count();

        return [
            'views_total' => $viewsTotal,
            'views_today' => (int) $counter->period('day')->count(),
            'views_week' => (int) $counter->period('week')->count(),
            'views_month' => (int) $counter->period('month')->count(),
        ];
    }

    public function conversionRate(int $applicationsCount, int $viewsTotal): float
    {
        if ($viewsTotal <= 0) {
            return 0.0;
        }

        return round(($applicationsCount / $viewsTotal) * 100, 1);
    }
}
