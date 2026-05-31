<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Interview;

class InterviewCalendarController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->can('interviews.calendar'), 403);

        $query = Interview::query()->with('candidate', 'interviewer');

        if (auth()->user()?->can('interviews.view_all')) {
            // Full visibility.
        } elseif (auth()->user()?->can('interviews.view_department')) {
            $departmentIds = auth()->user()->departments()->pluck('departments.id');
            $query->whereIn('department_id', $departmentIds);
        } elseif (auth()->user()?->can('interviews.view_own')) {
            $query->where('interviewer_id', auth()->id());
        } else {
            $query->whereRaw('1 = 0');
        }

        return view('hrm.interviews.calendar', [
            'interviews' => $query->orderBy('start_at')->get(),
        ]);
    }
}
