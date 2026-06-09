<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Services\HRM\DashboardMetricsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, DashboardMetricsService $metricsService)
    {
        abort_unless(auth()->user()?->can('dashboard.view'), 403);

        $range = $metricsService->normalizeRange((string) $request->query('range', '30d'));

        return view('hrm.dashboard', $metricsService->get($range));
    }
}
