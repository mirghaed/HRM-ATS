<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\HRM\GeneralApplicationRequest;
use App\Http\Requests\HRM\PublicJobApplicationRequest;
use App\Models\Application;
use App\Models\ApplicationSource;
use App\Models\Candidate;
use App\Models\CareerLandingSection;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\RecruitmentStatus;
use App\Services\Captcha\NumericCaptchaService;
use App\Services\HRM\ApplicationCreator;

class CareerController extends Controller
{
    public function index()
    {
        $sections = CareerLandingSection::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $departments = Department::query()
            ->where('is_active', true)
            ->withCount([
                'jobPositions as open_positions_count' => fn ($query) => $query->where('status', 'published'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $jobPositions = JobPosition::query()
            ->with('department:id,name,slug')
            ->where('status', 'published')
            ->orderByDesc('priority')
            ->orderByDesc('opened_at')
            ->orderByDesc('id')
            ->get();

        $hiredStatusId = RecruitmentStatus::query()->where('is_success', true)->value('id');
        $hiredCount = $hiredStatusId
            ? Application::query()->where('current_status_id', $hiredStatusId)->count()
            : 0;

        $stats = [
            'open_jobs' => $jobPositions->count(),
            'active_departments' => $departments->count(),
            'applications_total' => Application::query()->count(),
            'candidates_total' => Candidate::query()->count(),
            'hired_total' => $hiredCount,
        ];

        return view('careers.index', [
            'sections' => $sections,
            'departments' => $departments,
            'jobPositions' => $jobPositions,
            'stats' => $stats,
            'employmentTypes' => $this->employmentTypeLabels(),
            'workModes' => $this->workModeLabels(),
        ]);
    }

    public function jobs()
    {
        $departments = Department::query()
            ->where('is_active', true)
            ->withCount([
                'jobPositions as open_positions_count' => fn ($query) => $query->where('status', 'published'),
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $jobPositions = JobPosition::query()
            ->with('department:id,name,slug')
            ->where('status', 'published')
            ->orderByDesc('priority')
            ->orderByDesc('opened_at')
            ->orderByDesc('id')
            ->get();

        return view('careers.jobs.index', [
            'departments' => $departments,
            'jobPositions' => $jobPositions,
            'employmentTypes' => $this->employmentTypeLabels(),
            'workModes' => $this->workModeLabels(),
        ]);
    }

    public function show(JobPosition $jobPosition)
    {
        abort_if($jobPosition->status !== 'published', 404);

        $jobPosition->load('department', 'questions', 'requiredSkills');

        $similarJobs = JobPosition::query()
            ->with('department:id,name,slug')
            ->where('status', 'published')
            ->whereKeyNot($jobPosition->id)
            ->when(
                $jobPosition->department_id,
                fn ($query) => $query->where('department_id', $jobPosition->department_id)
            )
            ->orderByDesc('priority')
            ->orderByDesc('opened_at')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('careers.show', [
            'jobPosition' => $jobPosition,
            'similarJobs' => $similarJobs,
            'employmentTypes' => $this->employmentTypeLabels(),
            'workModes' => $this->workModeLabels(),
        ]);
    }

    public function apply(PublicJobApplicationRequest $request, JobPosition $jobPosition, ApplicationCreator $creator, NumericCaptchaService $captchaService)
    {
        abort_if($jobPosition->status !== 'published', 404);

        $sourceId = ApplicationSource::query()->where('key', 'website_form')->value('id');
        $payload = $request->validated();

        if (! empty($payload['expected_salary'])) {
            $payload['expected_salary_min'] = (int) $payload['expected_salary'];
            $payload['expected_salary_max'] = (int) $payload['expected_salary'];
        }

        $application = $creator->create([
            ...$payload,
            'city' => 'تهران',
            'job_position_id' => $jobPosition->id,
            'department_id' => $jobPosition->department_id,
            'source_id' => $sourceId,
            'form_answers' => $request->input('answers', []),
        ], $request->file('resume'));

        $captchaService->forget();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'رزومه شما با موفقیت ثبت شد.',
                'tracking_code' => $application->tracking_code,
            ], 201);
        }

        return redirect()->route('careers.jobs.show', $jobPosition)->with('success', 'رزومه شما با موفقیت ثبت شد. کد رهگیری: '.$application->tracking_code);
    }

    public function generalApply(GeneralApplicationRequest $request, ApplicationCreator $creator, NumericCaptchaService $captchaService)
    {
        $sourceId = ApplicationSource::query()->where('key', 'website_form')->value('id');
        $payload = $request->validated();

        $preferredDepartmentId = $payload['preferred_department_id'] ?? null;
        if ($preferredDepartmentId && empty($payload['department_id'])) {
            $payload['department_id'] = $preferredDepartmentId;
        }

        if (! empty($payload['expected_salary'])) {
            $payload['expected_salary_min'] = (int) $payload['expected_salary'];
            $payload['expected_salary_max'] = (int) $payload['expected_salary'];
        }

        if (! empty($payload['preferred_job_title']) && empty($payload['current_job_title'])) {
            $payload['current_job_title'] = $payload['preferred_job_title'];
        }

        if (! empty($payload['job_position_id']) && empty($payload['department_id'])) {
            $payload['department_id'] = JobPosition::query()->whereKey($payload['job_position_id'])->value('department_id');
        }

        $application = $creator->create([
            ...$payload,
            'city' => 'تهران',
            'source_id' => $sourceId,
            'source_reference' => 'general_application',
        ], $request->file('resume'));

        $captchaService->forget();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'رزومه عمومی شما با موفقیت ثبت شد.',
                'tracking_code' => $application->tracking_code,
            ], 201);
        }

        return redirect()->route('careers.index')->with('success', 'رزومه عمومی شما ثبت شد. کد رهگیری: '.$application->tracking_code);
    }

    private function employmentTypeLabels(): array
    {
        return [
            'full_time' => 'تمام‌وقت',
            'part_time' => 'پاره‌وقت',
            'project_based' => 'پروژه‌ای',
            'internship' => 'کارآموزی',
            'contract' => 'قراردادی',
        ];
    }

    private function workModeLabels(): array
    {
        return [
            'onsite' => 'حضوری',
            'remote' => 'دورکاری',
            'hybrid' => 'هیبرید',
        ];
    }
}
