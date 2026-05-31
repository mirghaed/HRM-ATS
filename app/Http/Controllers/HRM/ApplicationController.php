<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\ApplicationStoreRequest;
use App\Models\Application;
use App\Models\ApplicationSource;
use App\Models\CandidateFile;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\RecruitmentStatus;
use App\Models\SmsTemplate;
use App\Services\HRM\ApplicationCreator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Application::class);

        $query = Application::query()->with(['candidate', 'jobPosition', 'department', 'source', 'currentStatus']);

        if (auth()->user()?->can('applications.view_all')) {
            // Full visibility.
        } elseif (auth()->user()?->can('applications.view_department')) {
            $departmentIds = auth()->user()->departments()->pluck('departments.id');
            $query->whereIn('department_id', $departmentIds);
        } elseif (auth()->user()?->can('applications.view_assigned')) {
            $query->where('assigned_recruiter_id', auth()->id());
        } else {
            $query->whereRaw('1 = 0');
        }

        if ($statusId = $request->integer('status_id')) {
            $query->where('current_status_id', $statusId);
        }

        if ($departmentId = $request->integer('department_id')) {
            $query->where('department_id', $departmentId);
        }

        if ($search = trim((string) $request->get('q'))) {
            $query->whereHas('candidate', function ($subQuery) use ($search) {
                $subQuery
                    ->where('full_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return view('hrm.applications.index', [
            'applications' => $query->latest()->paginate(20)->withQueryString(),
            'statuses' => RecruitmentStatus::query()->orderBy('sort_order')->get(),
            'departments' => Department::query()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Application::class);

        return view('hrm.applications.create', [
            'sources' => ApplicationSource::query()->where('is_active', true)->orderBy('name')->get(),
            'jobPositions' => JobPosition::query()->where('status', 'published')->orderBy('title')->get(),
            'departments' => Department::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(ApplicationStoreRequest $request, ApplicationCreator $creator)
    {
        $application = $creator->create($request->validated(), $request->file('resume'));

        return redirect()->route('hrm.applications.show', $application)->with('success', 'رزومه با موفقیت ثبت شد.');
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);

        return view('hrm.applications.show', [
            'application' => $application->load([
                'candidate',
                'jobPosition',
                'department',
                'source',
                'currentStatus',
                'statusHistories.fromStatus',
                'statusHistories.toStatus',
                'notes.user',
                'interviews.interviewer',
                'activities.user',
                'files',
            ]),
            'statuses' => RecruitmentStatus::query()->orderBy('sort_order')->get(),
            'smsTemplates' => SmsTemplate::query()->where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function edit(Application $application)
    {
        $this->authorize('update', $application);

        return view('hrm.applications.edit', [
            'application' => $application,
            'sources' => ApplicationSource::query()->where('is_active', true)->orderBy('name')->get(),
            'jobPositions' => JobPosition::query()->orderBy('title')->get(),
            'departments' => Department::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ApplicationStoreRequest $request, Application $application)
    {
        $this->authorize('update', $application);

        $application->update($request->validated());

        return redirect()->route('hrm.applications.show', $application)->with('success', 'رزومه بروزرسانی شد.');
    }

    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);

        foreach ($application->files as $file) {
            $disk = $file->disk ?: 'private';
            if ($file->path && Storage::disk($disk)->exists($file->path)) {
                Storage::disk($disk)->delete($file->path);
            }
            $file->delete();
        }

        $application->delete();

        return redirect()->route('hrm.applications.index')->with('success', 'رزومه حذف شد.');
    }

    public function downloadFile(Application $application, CandidateFile $file)
    {
        $this->authorize('view', $application);

        abort_unless($file->application_id === $application->id, 404);

        $disk = $file->disk ?: 'private';
        abort_unless(Storage::disk($disk)->exists($file->path), 404);

        return Storage::disk($disk)->download($file->path, $file->original_name ?: basename($file->path));
    }
}
