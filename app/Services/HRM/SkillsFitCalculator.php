<?php

namespace App\Services\HRM;

use App\Models\Application;

class SkillsFitCalculator
{
    public function calculate(Application $application): ?int
    {
        $job = $application->jobPosition;
        if (! $job) {
            return null;
        }

        $required = $job->requiredSkills;
        if ($required->isEmpty()) {
            return null;
        }

        $candidateSkillIds = $application->candidate->skills()->pluck('skills.id')->all();

        $totalWeight = (int) $required->sum(fn ($skill) => (int) ($skill->pivot->weight ?: 1));
        if ($totalWeight === 0) {
            return null;
        }

        $matchedWeight = (int) $required->sum(function ($skill) use ($candidateSkillIds) {
            if (in_array($skill->id, $candidateSkillIds, true)) {
                return (int) ($skill->pivot->weight ?: 1);
            }

            return 0;
        });

        return (int) round(($matchedWeight / $totalWeight) * 100);
    }
}