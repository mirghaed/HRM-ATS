<?php

namespace App\Http\Requests\HRM;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobPositionRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $title = (string) $this->input('title', '');
        $slug = trim((string) $this->input('slug', ''));

        if ($slug === '' && $title !== '') {
            $slug = $this->generateSlugFromTitle($title);
        }

        $this->merge([
            'slug' => $slug,
            'status' => $this->input('status') ?: 'draft',
        ]);
    }

    private function generateSlugFromTitle(string $title): string
    {
        $latinSlug = Str::slug($title, '-', 'fa');
        if ($latinSlug !== '') {
            return $latinSlug;
        }

        $unicodeSlug = Str::of($title)
            ->lower()
            ->replaceMatches('/[^\p{Arabic}\p{N}\s-]+/u', '')
            ->replaceMatches('/[\s\-_]+/u', '-')
            ->trim('-')
            ->value();

        return $unicodeSlug !== '' ? $unicodeSlug : ('job-' . now()->format('YmdHis'));
    }

    public function authorize(): bool
    {
        return $this->user()?->can($this->isMethod('POST') ? 'job_positions.create' : 'job_positions.update') ?? false;
    }

    public function rules(): array
    {
        $jobPositionId = $this->route('job_position')?->id;

        return [
            'department_id' => ['required', 'exists:departments,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('job_positions', 'slug')->ignore($jobPositionId)],
            'description' => ['nullable', 'string'],
            'responsibilities' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'employment_type' => ['required', Rule::in(['full_time', 'part_time', 'project_based', 'internship', 'contract'])],
            'work_mode' => ['required', Rule::in(['onsite', 'remote', 'hybrid'])],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0', 'gte:salary_min'],
            'salary_currency' => ['nullable', 'string', 'max:10'],
            'is_salary_visible_public' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(['draft', 'published', 'paused', 'closed', 'archived'])],
            'is_public' => ['nullable', 'boolean'],
            'default_recruiter_id' => ['nullable', 'exists:users,id'],
            'default_interviewer_id' => ['nullable', 'exists:users,id'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'integer'],
            'opened_at' => ['nullable', 'date'],
            'closed_at' => ['nullable', 'date', 'after_or_equal:opened_at'],
            'questions' => ['nullable', 'array'],
            'questions.*.question' => ['required_with:questions', 'string', 'max:1000'],
            'questions.*.type' => ['required_with:questions', Rule::in(['text', 'textarea', 'number', 'select', 'radio', 'checkbox', 'file'])],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.is_required' => ['nullable', 'boolean'],
            'questions.*.sort_order' => ['nullable', 'integer'],
        ];
    }
}
