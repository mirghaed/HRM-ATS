<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\JobPositionRequest;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\User;

class JobPositionController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', JobPosition::class);

        return view('hrm.job_positions.index', [
            'jobPositions' => JobPosition::query()->with('department')->latest()->paginate(15),
        ]);
    }

    public function create()
    {
        $this->authorize('create', JobPosition::class);

        return view('hrm.job_positions.create', [
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function store(JobPositionRequest $request)
    {
        $data = $request->validated();
        $questions = $data['questions'] ?? [];
        unset($data['questions']);

        $data['status'] = $data['status'] ?? 'draft';
        $data['location'] = 'تهران';
        $data['created_by'] = auth()->id();

        $jobPosition = JobPosition::create($data);

        foreach ($questions as $question) {
            $jobPosition->questions()->create([
                'question' => $question['question'],
                'type' => $question['type'],
                'options' => $question['options'] ?? null,
                'is_required' => (bool) ($question['is_required'] ?? false),
                'sort_order' => $question['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('hrm.job-positions.index')->with('success', 'موقعیت شغلی جدید ایجاد شد.');
    }

    public function show(JobPosition $job_position)
    {
        $this->authorize('view', $job_position);

        return view('hrm.job_positions.show', [
            'jobPosition' => $job_position->load('department', 'questions'),
        ]);
    }

    public function edit(JobPosition $job_position)
    {
        $this->authorize('update', $job_position);

        return view('hrm.job_positions.edit', [
            'jobPosition' => $job_position->load('questions'),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function update(JobPositionRequest $request, JobPosition $job_position)
    {
        $this->authorize('update', $job_position);

        $data = $request->validated();
        $questions = $data['questions'] ?? [];
        unset($data['questions']);
        $data['location'] = 'تهران';

        $job_position->update($data);

        $job_position->questions()->delete();
        foreach ($questions as $question) {
            $job_position->questions()->create([
                'question' => $question['question'],
                'type' => $question['type'],
                'options' => $question['options'] ?? null,
                'is_required' => (bool) ($question['is_required'] ?? false),
                'sort_order' => $question['sort_order'] ?? 0,
            ]);
        }

        return redirect()->route('hrm.job-positions.index')->with('success', 'موقعیت شغلی به‌روزرسانی شد.');
    }

    public function destroy(JobPosition $job_position)
    {
        $this->authorize('delete', $job_position);

        $job_position->delete();

        return redirect()->route('hrm.job-positions.index')->with('success', 'موقعیت شغلی حذف شد.');
    }
}