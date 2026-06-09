<?php

namespace App\Services\HRM;

use App\Events\ApplicationCreated;
use App\Models\Application;
use App\Models\ApplicationAnswer;
use App\Models\ApplicationSource;
use App\Models\ApplicationStatusHistory;
use App\Models\CandidateFile;
use App\Models\RecruitmentStatus;
use App\Jobs\HRM\ParseResumeFileJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ApplicationCreator
{
    public function __construct(
        private readonly CandidateResolver $candidateResolver,
        private readonly SalaryFitCalculator $salaryFitCalculator,
        private readonly SkillsFitCalculator $skillsFitCalculator,
        private readonly TrackingCodeGenerator $trackingCodeGenerator,
    ) {
    }

    public function create(array $payload, ?UploadedFile $resumeFile = null): Application
    {
        return DB::transaction(function () use ($payload, $resumeFile) {
            $candidate = $this->candidateResolver->resolve($payload);

            $statusId = $payload['current_status_id']
                ?? RecruitmentStatus::query()->where('is_default', true)->value('id')
                ?? RecruitmentStatus::query()->orderBy('sort_order')->value('id');

            $application = Application::create([
                'tracking_code' => $this->trackingCodeGenerator->generate(),
                'candidate_id' => $candidate->id,
                'job_position_id' => $payload['job_position_id'] ?? null,
                'department_id' => $payload['department_id'] ?? null,
                'source_id' => $payload['source_id'] ?? ApplicationSource::query()->where('key', 'manual')->value('id'),
                'source_reference' => $payload['source_reference'] ?? null,
                'source_profile_url' => $payload['source_profile_url'] ?? null,
                'current_status_id' => $statusId,
                'expected_salary_min' => Arr::get($payload, 'expected_salary_min'),
                'expected_salary_max' => Arr::get($payload, 'expected_salary_max'),
                'cover_letter' => Arr::get($payload, 'cover_letter'),
                'form_answers' => Arr::get($payload, 'form_answers'),
                'raw_payload' => Arr::get($payload, 'raw_payload'),
                'assigned_recruiter_id' => Arr::get($payload, 'assigned_recruiter_id'),
                'assigned_department_manager_id' => Arr::get($payload, 'assigned_department_manager_id'),
                'assigned_interviewer_id' => Arr::get($payload, 'assigned_interviewer_id'),
                'applied_at' => now(),
                'last_status_changed_at' => now(),
            ]);

            if ($application->current_status_id) {
                ApplicationStatusHistory::create([
                    'application_id' => $application->id,
                    'from_status_id' => null,
                    'to_status_id' => $application->current_status_id,
                    'changed_by' => auth()->id(),
                    'note' => 'ایجاد اولیه درخواست',
                    'meta' => ['trigger' => 'created'],
                ]);
            }

            $job = $application->jobPosition;
            if ($job) {
                $application->salary_fit_score = $this->salaryFitCalculator->calculate(
                    (int) $application->expected_salary_min,
                    (int) $application->expected_salary_max,
                    $job->salary_min,
                    $job->salary_max,
                );
                $application->skills_fit_score = $this->skillsFitCalculator->calculate($application);
                $application->overall_score = collect([
                    $application->salary_fit_score,
                    $application->skills_fit_score,
                ])->filter(fn ($value) => $value !== null)->avg();
                $application->save();
            }

            if ($resumeFile) {
                // Guard against malformed uploads (empty tmp path / invalid uploaded file).
                if (! $resumeFile->isValid() || blank($resumeFile->getPathname())) {
                    throw ValidationException::withMessages([
                        'resume' => 'فایل رزومه معتبر نیست. لطفاً فایل PDF را دوباره انتخاب و ارسال کنید.',
                    ]);
                }

                $disk = 'private';
                $directory = 'resumes';
                Storage::disk($disk)->makeDirectory($directory);

                try {
                    $path = Storage::disk($disk)->putFile($directory, $resumeFile);
                } catch (\ValueError) {
                    throw ValidationException::withMessages([
                        'resume' => 'در ذخیره‌سازی فایل رزومه خطا رخ داد. لطفاً دوباره تلاش کنید.',
                    ]);
                }

                if (blank($path)) {
                    throw ValidationException::withMessages([
                        'resume' => 'فایل رزومه ذخیره نشد. لطفاً دوباره تلاش کنید.',
                    ]);
                }

                $candidateFile = CandidateFile::create([
                    'candidate_id' => $candidate->id,
                    'application_id' => $application->id,
                    'type' => 'resume',
                    'disk' => $disk,
                    'path' => $path,
                    'original_name' => $resumeFile->getClientOriginalName(),
                    'mime_type' => $resumeFile->getClientMimeType(),
                    'size' => $resumeFile->getSize(),
                    'uploaded_by' => auth()->id(),
                ]);

                ParseResumeFileJob::dispatch($candidateFile->id);
            }

            foreach ((array) Arr::get($payload, 'form_answers', []) as $questionId => $answer) {
                if (! is_numeric($questionId)) {
                    continue;
                }

                ApplicationAnswer::updateOrCreate(
                    [
                        'application_id' => $application->id,
                        'question_id' => (int) $questionId,
                    ],
                    [
                        'answer_text' => is_scalar($answer) ? (string) $answer : null,
                        'answer_json' => is_array($answer) ? $answer : null,
                    ],
                );
            }

            event(new ApplicationCreated($application));

            return $application;
        });
    }
}
