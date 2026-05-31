<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Department;
use App\Models\Interview;
use App\Models\JobPosition;

class DashboardController extends Controller
{
    public function index()
    {
        return view('hrm.dashboard', [
            'departmentCount' => Department::query()->count(),
            'openJobCount' => JobPosition::query()->where('status', 'published')->count(),
            'applicationCount' => Application::query()->count(),
            'pendingInterviews' => Interview::query()->where('status', 'scheduled')->where('start_at', '>=', now())->count(),
            'recentApplications' => Application::query()->with('candidate', 'jobPosition', 'currentStatus')->latest()->take(8)->get(),
        ]);
    }
}