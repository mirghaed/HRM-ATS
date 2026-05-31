<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\JobPosition;
use App\Models\RecruitmentStatus;

class ReportController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('reports.view'), 403);

        $totalApplications = Application::query()->count();
        $totalOpenPositions = JobPosition::query()->where('status', 'published')->count();
        $hiredCount = Application::query()->whereHas('currentStatus', fn ($query) => $query->where('is_success', true))->count();

        $statusStats = RecruitmentStatus::query()
            ->withCount('applications')
            ->orderBy('sort_order')
            ->get();

        $sourceStats = Application::query()
            ->selectRaw('source_id, COUNT(*) as total')
            ->with('source:id,name')
            ->groupBy('source_id')
            ->orderByDesc('total')
            ->get();

        $departmentStats = Application::query()
            ->selectRaw('department_id, COUNT(*) as total')
            ->with('department:id,name')
            ->groupBy('department_id')
            ->orderByDesc('total')
            ->get();

        $interviewStats = Interview::query()
            ->selectRaw("interviewer_id, COUNT(*) as total, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_total")
            ->with('interviewer:id,name')
            ->groupBy('interviewer_id')
            ->orderByDesc('total')
            ->get();

        $interviewCount = Interview::query()->count();
        $conversionToInterview = $totalApplications > 0 ? round(($interviewCount / $totalApplications) * 100, 2) : 0;
        $conversionToHired = $interviewCount > 0 ? round(($hiredCount / $interviewCount) * 100, 2) : 0;

        return view('hrm.reports.index', compact(
            'totalApplications',
            'totalOpenPositions',
            'hiredCount',
            'statusStats',
            'sourceStats',
            'departmentStats',
            'interviewStats',
            'conversionToInterview',
            'conversionToHired',
        ));
    }
}
