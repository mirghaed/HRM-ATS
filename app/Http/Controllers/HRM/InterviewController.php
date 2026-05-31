<?php

namespace App\Http\Controllers\HRM;

use App\Events\InterviewCancelled;
use App\Events\InterviewRescheduled;
use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\InterviewRequest;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use App\Services\HRM\InterviewScheduler;

class InterviewController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Interview::class);

        $query = Interview::query()->with('candidate', 'application', 'interviewer');

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

        return view('hrm.interviews.index', [
            'interviews' => $query->latest('start_at')->paginate(20),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Interview::class);

        return view('hrm.interviews.create', [
            'applications' => Application::query()->with('candidate')->latest()->take(100)->get(),
            'interviewers' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(InterviewRequest $request, InterviewScheduler $scheduler)
    {
        $application = Application::query()->findOrFail($request->integer('application_id'));
        $interview = $scheduler->schedule($application, $request->validated());

        return redirect()->route('hrm.interviews.show', $interview)->with('success', 'مصاحبه ثبت شد.');
    }

    public function show(Interview $interview)
    {
        $this->authorize('view', $interview);

        return view('hrm.interviews.show', [
            'interview' => $interview->load('candidate', 'application', 'interviewer', 'scheduler', 'reminders'),
        ]);
    }

    public function edit(Interview $interview)
    {
        $this->authorize('update', $interview);

        return view('hrm.interviews.edit', [
            'interview' => $interview,
            'interviewers' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(InterviewRequest $request, Interview $interview)
    {
        $this->authorize('update', $interview);

        $beforeStartAt = $interview->start_at;
        $beforeStatus = $interview->status;

        $interview->update($request->validated());

        if ($beforeStartAt?->ne($interview->start_at)) {
            event(new InterviewRescheduled($interview->fresh()));
        }

        if ($beforeStatus !== 'cancelled' && $interview->status === 'cancelled') {
            event(new InterviewCancelled($interview->fresh()));
        }

        return redirect()->route('hrm.interviews.show', $interview)->with('success', 'مصاحبه بروزرسانی شد.');
    }

    public function destroy(Interview $interview)
    {
        $this->authorize('delete', $interview);

        $interview->update(['status' => 'cancelled']);
        event(new InterviewCancelled($interview->fresh()));

        return redirect()->route('hrm.interviews.index')->with('success', 'مصاحبه لغو شد.');
    }
}
