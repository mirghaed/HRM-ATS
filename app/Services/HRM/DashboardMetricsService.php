<?php

namespace App\Services\HRM;

use App\Models\Application;
use App\Models\Department;
use App\Models\Interview;
use App\Models\JobPosition;
use App\Models\RecruitmentStatus;
use App\Support\DashboardCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardMetricsService
{
    public function __construct(
        private readonly JobPositionVisitRecorder $visitRecorder,
    ) {
    }

    public function get(string $range = '30d'): array
    {
        $range = $this->normalizeRange($range);

        $cached = Cache::remember(
            DashboardCache::key($range),
            now()->addMinutes(5),
            fn () => $this->buildCachedMetrics($range),
        );

        return array_merge($cached, $this->buildLiveSections($range));
    }

    public function normalizeRange(string $range): string
    {
        return in_array($range, DashboardCache::RANGES, true) ? $range : '30d';
    }

    /**
     * @return array<string, mixed>
     */
    private function buildCachedMetrics(string $range): array
    {
        [$from, $visitPeriod] = $this->rangeBounds($range);

        $departmentsCount = Department::query()->where('is_active', true)->count();
        $openPositionsCount = JobPosition::query()->where('status', 'published')->count();
        $applicationsCount = Application::query()
            ->when($from, fn ($query) => $query->where('applied_at', '>=', $from))
            ->count();
        $upcomingInterviewsCount = Interview::query()
            ->where('status', 'scheduled')
            ->where('start_at', '>=', now())
            ->count();

        $positions = JobPosition::query()
            ->with('department:id,name')
            ->withCount('applications')
            ->where('status', 'published')
            ->orderByDesc('opened_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (JobPosition $position) {
                $stats = $this->visitRecorder->stats($position);

                return [
                    'id' => $position->id,
                    'title' => $position->title,
                    'department_name' => $position->department?->name,
                    'status' => 'فعال',
                    'applications_count' => (int) $position->applications_count,
                    'views_total' => $stats['views_total'],
                    'views_today' => $stats['views_today'],
                    'views_week' => $stats['views_week'],
                    'views_month' => $stats['views_month'],
                    'conversion_rate' => $this->visitRecorder->conversionRate(
                        (int) $position->applications_count,
                        $stats['views_total'],
                    ),
                    'admin_url' => route('hrm.job-positions.edit', $position),
                    'public_url' => route('careers.jobs.show', $position),
                    'updated_at' => optional($position->updated_at)->toIso8601String(),
                ];
            })
            ->sortByDesc('views_total')
            ->values()
            ->all();

        $totalViews = (int) collect($positions)->sum('views_total');
        $todayViews = (int) collect($positions)->sum('views_today');
        $weekViews = (int) collect($positions)->sum('views_week');
        $monthViews = (int) collect($positions)->sum('views_month');
        $topPosition = $positions[0] ?? null;

        $rangeViews = match ($visitPeriod) {
            'day' => $todayViews,
            'week' => $weekViews,
            'month' => $monthViews,
            default => $monthViews,
        };

        $conversionRate = $totalViews > 0
            ? $this->visitRecorder->conversionRate($applicationsCount, $totalViews)
            : 0.0;

        return [
            'range' => $range,
            'rangeLabel' => $this->rangeLabel($range),
            'departmentsCount' => $departmentsCount,
            'openPositionsCount' => $openPositionsCount,
            'applicationsCount' => $applicationsCount,
            'upcomingInterviewsCount' => $upcomingInterviewsCount,
            'positions' => $positions,
            'totalViews' => $totalViews,
            'todayViews' => $todayViews,
            'weekViews' => $weekViews,
            'monthViews' => $monthViews,
            'rangeViews' => $rangeViews,
            'topPosition' => $topPosition,
            'conversionRate' => $conversionRate,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLiveSections(string $range): array
    {
        [$from] = $this->rangeBounds($range);

        $statusSummary = RecruitmentStatus::query()
            ->withCount(['applications as applications_count' => function ($query) use ($from) {
                if ($from) {
                    $query->where('applied_at', '>=', $from);
                }
            }])
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($status) => $status->applications_count > 0)
            ->map(fn ($status) => [
                'title' => $status->title,
                'applications_count' => (int) $status->applications_count,
            ])
            ->values()
            ->all();

        return [
            'latestApplications' => Application::query()
                ->with(['candidate', 'jobPosition', 'currentStatus'])
                ->latest('applied_at')
                ->limit(8)
                ->get(),
            'upcomingInterviews' => Interview::query()
                ->with(['candidate', 'jobPosition', 'interviewer'])
                ->where('status', 'scheduled')
                ->where('start_at', '>=', now())
                ->orderBy('start_at')
                ->limit(6)
                ->get(),
            'statusSummary' => $statusSummary,
        ];
    }

    /**
     * @return array{0: ?Carbon, 1: string}
     */
    private function rangeBounds(string $range): array
    {
        return match ($range) {
            'today' => [now()->startOfDay(), 'day'],
            '7d' => [now()->subDays(7)->startOfDay(), 'week'],
            default => [now()->subDays(30)->startOfDay(), 'month'],
        };
    }

    private function rangeLabel(string $range): string
    {
        return match ($range) {
            'today' => 'امروز',
            '7d' => '۷ روز اخیر',
            default => '۳۰ روز اخیر',
        };
    }
}
