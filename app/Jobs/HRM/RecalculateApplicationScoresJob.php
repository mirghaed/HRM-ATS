<?php

namespace App\Jobs\HRM;

use App\Models\Application;
use App\Services\HRM\SalaryFitCalculator;
use App\Services\HRM\SkillsFitCalculator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class RecalculateApplicationScoresJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public ?int $applicationId = null)
    {
    }

    public function handle(SalaryFitCalculator $salaryFitCalculator, SkillsFitCalculator $skillsFitCalculator): void
    {
        $query = Application::query()->with('jobPosition', 'candidate.skills');

        if ($this->applicationId) {
            $query->whereKey($this->applicationId);
        }

        $query->chunkById(100, function ($applications) use ($salaryFitCalculator, $skillsFitCalculator) {
            foreach ($applications as $application) {
                $job = $application->jobPosition;
                if (! $job) {
                    continue;
                }

                $salaryFit = $salaryFitCalculator->calculate(
                    (int) $application->expected_salary_min,
                    (int) $application->expected_salary_max,
                    $job->salary_min,
                    $job->salary_max,
                );

                $skillsFit = $skillsFitCalculator->calculate($application);

                $application->update([
                    'salary_fit_score' => $salaryFit,
                    'skills_fit_score' => $skillsFit,
                    'overall_score' => collect([$salaryFit, $skillsFit])->filter(fn ($value) => $value !== null)->avg(),
                ]);
            }
        });
    }
}